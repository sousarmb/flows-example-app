# demo-create-file-branch-write-from-db
[Script here](demo-create-file-branch-write-from-db.php)

This demo flow uses 5 processes:
* [`CreateFileBranchProcess`](Processes/CreateFileBranchProcess.php)
* [`ParallelWriteProcess_A`](Processes/ParallelWriteProcess_A.php)
* [`ParallelWriteProcess_B`](Processes/ParallelWriteProcess_B.php)
* [`ParallelWriteProcess_C`](Processes/ParallelWriteProcess_C.php)
* [`WriteToFileWithAndGateProcess`](Processes/WriteToFileWithAndGateProcess.php)

It shows how to branch processes using the `And` and `Xor` gates, using one process to create a file and others to write to it, with data from a separate source. 

`CreateFileBranchProcess` process creates file `App/demo-text-file` and passes it to `WriteToFileWithAndGateProcess` using a `Xor` gate ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php) holds the file handle). 

`WriteToFileWithAndGateProcess` open a connection to a local database and stores it in a `IO` instance. The flow branches to processes `ParallelWriteProcess_A`, `ParallelWriteProcess_B` and `ParallelWriteProcess_C` using a `And` gate, each process receiving as input the `IO` instance returned from the previous task. These processes retrieve data from the database (using the connection in `IO`) and write to the file. 

> When branching take care: previous `IO` instance may be lost if not returned by one of the branched processes.

> `*AND` gates branch to every registered process in order of registration. When all branches are done, flow resumes at the last branching point.

> `Xor` gates branch to one process to continue the flow.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.

When process `ParallelWriteProcess_A`, `ParallelWriteProcess_B` and `ParallelWriteProcess_C` process branches end, flow resumes in `WriteToFileWithAndGateProcess` and the file contents are dumped. 

When `WriteToFileWithAndGateProcess` is finished clean up occurs: database connection and file are closed.

> When process A uses an `Xor` gate to branch to process B, the flow does not return process A when B is finished.

> The output of the branched processes is always available in the task input (`$io`) to the next process int the flow. To get it `get()` using the member variable name (`string`).
