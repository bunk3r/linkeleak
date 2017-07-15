#!/bin/bash
for F in "$@"
do
 if [ ! -f "$F.ori" ]; then
 	echo "Transforming $F to TSV with sed (.ori backup)..."
 	sed -i.ori 's/:/\t/' $F
 fi
 echo "Importing into mongodb with mongoimport..."
 mongoimport -d linkeleak -c hashpass --fields=hash,password --type=tsv --file $F

done
mongo localhost/linkeleak --eval 'db.hashpass.createIndex( { hash : 1} )'
#, { unique:true,dropDups:true } )'

if [[ $# -eq 0 ]]; then
 echo "Usage: $0 file1 [file2] [file3] [..]"
 exit 1
fi
