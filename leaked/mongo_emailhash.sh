#!/bin/bash
for F in "$@"
do
 if [ ! -f "$F.ori" ]; then
 	echo "Transforming $F to TSV with sed (.ori backup)..."
 	sed -i.ori 's/:/\t/' $F
 fi
 echo "Importing into mongodb with mongoimport..."
 mongoimport -d linkeleak -c emailhash --fields=email,hash --type=tsv --file $F
done

mongo localhost/linkeleak --eval 'db.emailhash.createIndex( { hash : 1 } )'
#, { unique:true,dropDups:true } )'


if [[ $# -eq 0 ]]; then
 echo "Usage: $0 file1 [file2] [file3] [..]"
 exit 1
fi
#mongoimport -d linkeleak -c hashes --upsert --upsertFields=hash --fields=hash,password --type=csv --file 1_1_potparse.csv
