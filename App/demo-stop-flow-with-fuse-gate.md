# demo-stop-flow-with-fuse-gate
[Script here](demo-stop-flow-with-fuse-gate.php)

This demo flow uses 1 process:
* [`StopFlowWithFuseGateProcess`](Processes/StopFlowWithFuseGateProcess.php)

It shows how to create a process made up of [`Task`](https://github.com/sousarmb/flows/blob/main/src/Contracts/Tasks/Task.php)s. `StateChangeWithSaveAndUndoProcess` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it.

One of the process steps is a [FuseGate](https://github.com/sousarmb/flows/blob/main/src/Gates/FuseGate.php), where the developer set logic to interrupt the process and flow if necessary.

> [FuseGate](https://github.com/sousarmb/flows/blob/main/src/Gates/FuseGate.php) instances can interrupt flow, returning control back to the application kernel, which stops the flow gracefully: triggers deferred event and observation processing. The gate also controls if the last task output (`IO` instance) is returned to application kernel, to be the flow result. 

When the flow ends, the counter is dumped.
