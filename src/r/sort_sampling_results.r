#infile = "/home/klaus/Code/PHP/isce.cube/results/PSPTO_4776_AT5G02500.1/algorithms/basc.clear_pc"
#outfile = "/home/klaus/Code/PHP/isce.cube/results/PSPTO_4776_AT5G02500.1/algorithms/basc.clear_pc_sort"
args <- commandArgs(TRUE)     
infile = args[1]
outfile = args[2]
input <- read.csv(infile,header=TRUE,sep="\t")
input_oder <- order(input$score, decreasing = T)
write(c("i","j","score"), outfile, sep="\t", append=TRUE, ncolumns=3)
for(x in input_oder){
  write(c(input[x,1],input[x,2],input[x,3]), outfile, sep="\t", append=TRUE, ncolumns=3)
}