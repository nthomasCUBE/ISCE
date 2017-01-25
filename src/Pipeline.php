<?php 	

namespace ISCE;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use ISCE\OrthoFinder;
use ISCE\Config;

use Bio\IO\File\OrthoFinderFile;
use Bio\IO\File\MappingFile;

use Bio\IO\File\FastaFile;
use Bio\Tool\Shell;
use Bio\Tool\Needle;
use Bio\Tool\Muscle;
use Bio\Tool\Algorithm;
use Bio\Tool\Sampling;
use Bio\Tool\Decoy;
use Bio\Tool\Filter;
use Bio\Tool\Results;
use Bio\Tool\Log;

ini_set('memory_limit', '4096M');

class Pipeline extends Command{

    /**
     * Set up the run command for the pipeline
     *
     * Command Syntax: php isce.php run LocusA LocusB
     * 
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('run:locus')
            ->setDescription('Run the pipeline')
            ->addArgument(
                'locusA',
                InputArgument::REQUIRED,
                'Reference Locus ID of Group A'
            )
            ->addArgument(
                'locusB',
                InputArgument::REQUIRED,
                'Reference Locus ID of Group B'
            )
            ->addOption(
                's',
                null,
                InputOption::VALUE_NONE,
                'Sample?'
            );
    }

    /**
     * Initialize the pipeline environment including default paths and config object
     * 
     * @param  InputInterface  $input  Symfony IO Handlder
     * @param  OutputInterface $output Symfony IO Handlder
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {  

        # Display a nice welcome message
        $output->writeln('Inter Species Co-Evolution Pipeline');
        $output->writeln('Version: ' . __VER__ .' Date: ' . __DATE__);
        $output->writeln('Klaus Rembart <klaus@rembart.at>');
        
        try {

            

            $this->fs = new Filesystem();

            $this->param['locus']['A'] = $input->getArgument('locusA');
            $this->param['locus']['B'] = $input->getArgument('locusB');

            $this->files['config'] = 'isce.config.json';
            $this->files['mapping'] = 'mapping.config';
            $this->files['applog'] = 'app.log';

            $this->paths['home']    = __DIR__ . '/..';
            $this->paths['config']  = $this->paths['home'] . '/config';
            $this->paths['logs']    = $this->paths['home'] . '/logs';
            $this->paths['storage'] = $this->paths['home'] . '/results';

            $this->paths['results']['home']         = $this->paths['storage'] . '/' . $this->param['locus']['A'] . '_' . $this->param['locus']['B'];
            $this->paths['results']['algorithms']   = $this->paths['results']['home'] . '/algorithms';
            $this->paths['results']['msa']          = $this->paths['results']['home'] . '/msa';

            define('__LOG__', $this->paths['results']['home'] . '/isce.log' );


            

            $this->config = new Config($this->paths['config'] . '/' . $this->files['config']);
            $this->param['min'] = $this->config->get('config.minSize');

            if (!$this->fs->exists($this->paths['storage'])) {
                $this->fs->mkdir($this->paths['storage']);
            }

            if (!$this->fs->exists($this->config->get('path.speciesA.fasta'))) {
                throw new \Exception("Fasta directory of speciesA was not found: ". $this->config->get('path.speciesA.fasta'));
            }
            if (!$this->fs->exists($this->config->get('path.speciesB.fasta'))) {
                throw new \Exception("Fasta directory of speciesB was not found: ". $this->config->get('path.speciesB.fasta'));
            }
            if (!$this->fs->exists($this->config->get('path.speciesA.index'))) {
                throw new \Exception("orthoFinder index file for species A not found: ". $this->config->get('path.speciesA.index'));
            }
            if (!$this->fs->exists($this->config->get('path.speciesB.index'))) {
                throw new \Exception("orthoFinder index file for species B not found: ". $this->config->get('path.speciesB.index'));
            }

            if ($this->fs->exists($this->paths['results']['home'])) {
                throw new \Exception("The reusult folder already exists!");
            }

            foreach ($this->paths['results'] as $folder) {
                $this->fs->mkdir($folder);
            }

            $this->fs->touch(__LOG__);

            Log::info("A:\t".$input->getArgument('locusA'));
            Log::info("B:\t".$input->getArgument('locusB'));
            Log::info("Setup Directories");

        }
        catch (\Exception $e) {
            $output->writeln('<error>Error: '.$e->getMessage().'</error>');
            exit();
        }

    }

    /**
     * Run the Pipeline 
     * 
     * @param  InputInterface  $input  Symfony IO Handlder
     * @param  OutputInterface $output Symfony IO Handlder
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try{
           
            # Read the two orthoFinder index files 
            $orthoIndexA = OrthoFinderFile::create($this->config->get('path.speciesA.index'));
            $orthoIndexB = OrthoFinderFile::create($this->config->get('path.speciesB.index'));
            $groupA = $orthoIndexA->getLocus($input->getArgument('locusA'));
            $groupB = $orthoIndexB->getLocus($input->getArgument('locusB'));
            $groupIdA = $orthoIndexA->getGroupId($input->getArgument('locusA'));
            $groupIdB = $orthoIndexB->getGroupId($input->getArgument('locusB')); 
            unset($orthoIndexA, $orthoIndexB);


            # Read the MappingFile     
            Log::info("Reading Mapping File: ". $this->paths['config'] . '/' . $this->files['mapping']);
            $map = new MappingFile($this->paths['config'] . '/' . $this->files['mapping']);

            # Map the two Ortho Groups
            Log::info("Mapping Groups, min. matches: ". $this->param['min']);
            $map->mapGroups($groupA, $groupB, $this->param['min']);

            # Get the sequences from the fasta file
            Log::info("Reading Fasta-Files (A): ". $this->config->get('path.speciesA.fasta'));          
            $seqs_a = $this->_seqs($map->A, $this->config->get('path.speciesA.fasta') );
            Log::info("Reading Fasta-Files (B): ". $this->config->get('path.speciesB.fasta'));    
            $seqs_b = $this->_seqs($map->B, $this->config->get('path.speciesB.fasta') );

            # Get the reference sequences (locusIDs) from fasta files
            Log::info("Reading Reference Sequence (A)");  
            $ref_a = $this->_refseq($groupA, $input->getArgument('locusA'), $this->config->get('path.speciesA.fasta'));
            $fh = fopen($this->paths['results']['msa'] . '/refseq.a', 'w');
            fwrite($fh, $ref_a);
            fclose($fh);
            unset($groupA);

            Log::info("Reading Reference Sequence (B)");  
            $ref_b = $this->_refseq($groupB, $input->getArgument('locusB'), $this->config->get('path.speciesB.fasta'));
            $fh = fopen($this->paths['results']['msa'] . '/refseq.b', 'w');
            fwrite($fh, $ref_b);
            fclose($fh);
            unset($groupB);

            # Rate all groups with more than one member according to the refseq (nwa)
            Log::info("Run NWA for Reference Sequence (A)");  
            $seqs_a = $this->_needle($seqs_a, $ref_a);
            Log::info("Run NWA for Reference Sequence (B)"); 
            $seqs_b = $this->_needle($seqs_b, $ref_b);   

            #Filter::create(Muscle::make($seqs_a), Muscle::make($seqs_b), $this->paths['results']['msa'])->rows($this->param['min'])->columns();
            Filter::create(Muscle::make($seqs_a), Muscle::make($seqs_b), $this->paths['results']['msa'])->columns();
            unset($seqs_a, $seqs_b);

            $msa_a = new FastaFile($this->paths['results']['msa'] . '/filter.a.msa');
            $msa_b = new FastaFile($this->paths['results']['msa'] . '/filter.b.msa'); 

            # Run the coev algorithms 
            $alg = new Algorithm();
            #$alg->all($msa_a, $msa_b, $map, $this->paths['results']['home']);
            $alg->all_fixed_size($msa_a, $msa_b, $map, $this->paths['results']['home'],$this->param['min']);
           

            Log::info("Pipeline finished.");
            Log::info("Real time: " . ((microtime(true) - __TIME__) /60) . " min");
            Log::h1();
        
        }
        catch (\Exception $e) {
            $output->writeln('<error>Error: '.$e->getMessage().'</error>');
            Log::error($e->getMessage());
            unset($this->algorithms);
            unset($this->samplings);
            exit();
        }
    }

    private function _needle($seqs, $ref)
    {
        $result = [];
        foreach ($seqs as $fasta => $data) {

            if (sizeof($data) > 1) {
                $scored = array();
                foreach ( $data as $locus_id => $attributes) {
                    $cmd = new Needle($ref, $attributes['seq']);
                    $scored[$locus_id] = $cmd->exe();
                    $seqs[$fasta][$locus_id]['score'] = $scored[$locus_id];
                    Log::info('NWA:' . "\t" .  $fasta . "\t" . $locus_id . "\t" . $seqs[$fasta][$locus_id]['score'] ); 
                }
                arsort($scored, SORT_NUMERIC);
                array_push($result, $seqs[$fasta][key($scored)]);
            }
            else {
                array_push($result, array_shift($data));
            }
        }
        return $result;
    }

    private function _seqs($group, $dir)
    {
        foreach ($group as $fasta_name => $locus_array) {
            $ff = new FastaFile($dir . $fasta_name);
            foreach ($locus_array as $index => $locus_id) {
                $seqs[$fasta_name][$locus_id]['fasta'] = $fasta_name;
                $seqs[$fasta_name][$locus_id]['id'] = $locus_id;
                $seqs[$fasta_name][$locus_id]['seq'] = $ff->findSeqById($locus_id);
                $seqs[$fasta_name][$locus_id]['score'] = 0;
            }
        }
        return $seqs;
    }

    private function _refseq($group_clean, $ref_id, $dir) 
    {
        foreach ($group_clean as $fasta_name => $locus_array) {
            foreach ($locus_array as $nr => $locus_id) {
                if ($ref_id === $locus_id) {
                    $ff = new FastaFile($dir . $fasta_name);
                    return $ff->findSeqById($ref_id);
                }
            }
        }
        throw new \Exception("A locusID doesn't match the mapping rules :(");
    }

}