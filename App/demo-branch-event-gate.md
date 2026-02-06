# demo-branch-event-gate
[Script here](demo-branch-event-gate.php)

This demo flow uses 3 processes:
* [`AfterFileModificationProcess`](Processes/AfterFileModificationProcess.php)
* [`DefaultProcess`](Processes/DefaultProcess.php)
* [`WaitForFileModificationProcess`](Processes/WaitForFileModificationProcess.php)

It shows how to branch flow using the [`Event`](https://github.com/sousarmb/flows/blob/main/src/Gates/EventGate.php) gate. `WaitForFileModificationProcess` process creates (and opens) a file - `App/demo-text-file` - and then passes the file handle to the next task that writes a string to it. The last step of the process is an event gate, that waits for one of 4 events to happen:
1. `App/demo-text-file` is modified
2. The next day (tomorrow)
3. A string is written to a named pipe `/tmp/myfifo` by an external process/program
4. An HTTP request is made to the resource `/get-it-going` with the `GET` method and `Authorization` header set to `Basic 1234`

These events are represented using [`GateEvent`](https://github.com/sousarmb/flows/blob/main/src/Gates/EventGate.php) instances.

> `Event` gates group events that must happen for the flow to branch. A gate event can be one of the following types:
>* **Frequent**, event is checked every n seconds
>* **HTTP**, event is checked when a request arrives for the given resource
>* **Stream**, event is checked when the stream is ready to be read

> Both the event gate and the gate events have expiration timers to prevent the gate from waiting forever.

When one of the events happens code in the gate decides where the flow branches:
. `AfterFileModificationProcess`, where the file contents are shown and the file is removed.
. `DefaultProcess`, where nothing happens.

> `IO` instances act as [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.

> Branching with `Event` gate is the same as branching with `Xor` gate, always flow forward.