# demo-branch-and-gate
[Script here](demo-branch-and-gate.php)

This demo flow uses 4 processes:
* [`BranchWithAndGateProcess`](Processes/BranchWithAndGateProcess.php)
* [`ParallelProcess_A`](Processes/ParallelProcess_A.php)
* [`ParallelProcess_B`](Processes/ParallelProcess_B.php)
* [`ParallelProcess_C`](Processes/ParallelProcess_C.php)

It shows how to branch processes using the `And` gate. `BranchWithAndGateProcess` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it. 

> `*AND` gates branch to every registered process in order of registration. When all branches are done, flow resumes at the last branching point.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.

It then branches using an `And` gate to run processes `ParallelProcess_A`, `ParallelProcess_B`, `ParallelProcess_C` in the same PHP process, giving them the same input: the output of `BranchWithAndGateProcess` last task before branching. 

Once the processes are finished, they're output is grouped into a [collection](https://github.com/sousarmb/collectibles/blob/main/src/Collection.php) and the flow resumes with `BranchWithAndGateProcess` next task.

> When process A uses an `And` gate to branch, when processes B, C, D, ... are finished, flow always resumes in process A, at the next task.

> The output of the branched processes is always available in the task input (`$io`) to the next process int the flow. To get it, `get()` using the `[process]::class` string.

The last task dumps the results of the offloaded processes plus the counter, in a map: PID => output.
