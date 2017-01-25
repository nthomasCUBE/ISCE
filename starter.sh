#!/bin/bash
#
#SBATCH --job-name=ce-is
#SBATCH --cpus-per-task=1
#SBATCH --mem=4000
#SBATCH --output=logs/spades-%j.out
#SBATCH --error=logs/spades-%j.err

php isce.php run:locus $1 $2
