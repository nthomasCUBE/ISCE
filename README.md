# ISCE

ISCE is a pipeline developed to obtain likely interacting protein between different species.
Therefore orthologous group for both, the pathogen and the host are provided in the backgroup and for a given
protein pair the correlated mutation algorithms are applied.

A. Calculation of orthologous groups

We have calculated orthologous groups for both, the pathogens and the hosts sepreately by using the tool
Orthofinder (Emms and Kelly, 2015). 

The orthologous groups needed for the calculation need to be stored under

data/speciesA/y2h.csv
data/speciesB/Plants.csv

Then, the correlated mutation analyses can be performed by taking one protein from the species A and one protein from speciesB.

By using the script "./starter.sh GENE_A GENE_B" can be launched and leading to the calculation of 
four different correlated mutation algorithms. Finally, the merged results from the four tools are reported to the user.
