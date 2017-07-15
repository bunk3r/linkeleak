#!/bin/bash
rm -r dump
echo "Dumping into /dump linkeleak db.."
mongodump -h localhost -d linkeleak -c merged

echo "dropp & re-create linkeleak db with unique email field"
mongo localhost/linkeleak --eval 'db.merged.drop();db.merged.insertOne( {email:null,hash:null,password:null} );db.merged.createIndex( { email : 1 }, { unique:true } )'

echo "restoring linkeleakdb..."
mongorestore -h localhost -d linkeleak dump/linkeleak/

echo "Done!"
