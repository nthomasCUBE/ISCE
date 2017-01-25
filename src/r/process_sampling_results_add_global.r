#i=1
#sample_size=100
#percentile = 0.90
#sample_file = "/scratch/rembart/ISCE/ISCE-1.7/out_mi_0" #actually i=1
#output = "/scratch/rembart/ISCE/ISCE-1.7/test_out"

args <- commandArgs(TRUE)     
i=as.numeric(args[1])
sample_size=as.numeric(args[2])
percentile = as.numeric(args[3])
sample_file = args[4]
output = args[5]

sampling <- read.csv(sample_file,header=TRUE,sep="\t")
colnames(sampling) <- c('rounds', 'j', 'score')
sampling <- subset(sampling,rounds < sample_size & j >= sample_size)
sampling[,1] <- sampling[,1]+1
sampling[,2] <- sampling[,2]+1 - sample_size

if(i==1){
  write(c("i","j","mean","sd","var",paste0("threshold(",percentile,")")), output, sep="\t", append=TRUE, ncolumns=6)
}

for(x in 1:max(sampling$j)){
  position <- subset(sampling, j==x,select=score)
  m <- mean(position$score)
  s <- sd(position$score)
  v <- var(position$score)
  t <- quantile(position$score, probs = c(percentile))[[1]]
  write(c(i,x,m,s,v,t), output, sep="\t", append=TRUE, ncolumns=6)
}
