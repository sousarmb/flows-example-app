# demo-branch-xor-gate
[Script here](demo-branch-xor-gate.php)

This demo flow uses 4 processes:
* [`DefaultProcess`](Processes/DefaultProcess.php)
* [`BranchWithXorGate_End_Process`](Processes/BranchWithXorGate_End_Process.php)
* [`BranchWithXorGate_Middle_Process`](Processes/BranchWithXorGate_Middle_Process.php)
* [`BranchWithXorGate_Start_Process`](Processes/BranchWithXorGate_Start_Process.php)

It shows how to branch processes using the `Xor` gate. `BranchWithXorGate_Start_Process` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it. 

> `Xor` gates branch to one process to continue the flow.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.

It then branches using an `Xor` gate to `BranchWithXorGate_Middle_Process` processes where the counter continues to be incremented. The last step is a `Xor` gate where logic is used to decide wether to branch to `DefaultProcess` or `BranchWithXorGate_End_Process` process and end the flow. 

If branched to `BranchWithXorGate_End_Process` the counter continues to be incremented and its value is shown when the flow ends.

> When process A uses an `Xor` gate to branch to process B, the flow does not return process A when B is finished.

> The output of the branched processes is always available in the task input (`$io`) to the next process int the flow. To get it `get()` using the member variable name (`string`).
