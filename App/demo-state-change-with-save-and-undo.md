# demo-state-change-with-save-and-undo
[Script here](demo-state-change-with-save-and-undo.php)

This demo flow uses 1 process:
* [`StateChangeWithSaveAndUndoProcess`](Processes/StateChangeWithSaveAndUndoProcess.php)

It shows how to create a process made up of [`Task`](https://github.com/sousarmb/flows/blob/main/src/Contracts/Tasks/Task.php)s. `StateChangeWithSaveAndUndoProcess` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it.

One of the process steps is a [save point](https://github.com/sousarmb/flows/blob/main/src/Processes/Sign/SaveState.php), where the process state is saved, then processing resumes.

> When a [`Process`](https://github.com/sousarmb/flows/blob/main/src/Processes/Process.php) encounters a [`SaveState`](https://github.com/sousarmb/flows/blob/main/src/Processes/Sign/SaveState.php) step, this causes the process state - step number + `IO` - to be saved, if necessary, the developer can "roll back" to this state and resume from there. 

The process continues with its tasks. Before the last task there is a [`UndoStateGate`](https://github.com/sousarmb/flows/blob/main/src/Gates/UndoStateGate.php), where the developer set logic to determine if a "roll back" to a previous process state is necessary.

> With [`UndoStateGate`](https://github.com/sousarmb/flows/blob/main/src/Gates/UndoStateGate.php) the developer can change process state without necessarily changing the flow. This gate returns an integer representing how many `SaveState`s to go back. 0 means continue (resume process, no "roll back").
>
> The developer cannot go back "forever": save states are stored in a stack and popped when needed, when the stack is empty an exception is thrown. The developer has to manage the "roll back"s to prevent this infinite loop use case. 

When the flow ends, the counter is dumped.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.