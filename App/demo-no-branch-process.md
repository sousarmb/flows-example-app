# demo-no-branch-process
[Script here](demo-no-branch-process.php)

This demo flow uses 1 process:
* [`NoBranchProcess`](Processes/NoBranchProcess.php)

It shows how to create a process made up of [`Task`](https://github.com/sousarmb/flows/blob/main/src/Contracts/Tasks/Task.php)s. `NoBranchProcess` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it. 

When the flow ends the counter is dumped.

> `Xor` gates branch to one process to continue the flow.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.