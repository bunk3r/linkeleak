//db.merged.dropIndex({ "email":1 });

db.emailhash.aggregate([
    {	$match: { password : null} },
    {	$sort: { hash : 1 } },
    {
        $lookup: {
            from: "hashpass",
            localField: "hash",
            foreignField: "hash",
            as: "cracked"
        },
    },
    { $unwind: { path: "$cracked", preserveNullAndEmptyArrays: false }},
    { $project: { _id: 0, email: 1, hash: 1, password: "$cracked.password"   } },
    { $out: "merged" }
],
	{ allowDiskUse: true }
//	,{ explain: true }
)
db.emailhash.drop();
db.hashpass.drop();
