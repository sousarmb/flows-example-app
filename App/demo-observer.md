# demo-observer
[Script here](demo-observer.php)

This demo flow uses 4 processes:
* [`ObserveAndOffloadGateProcess`](Processes/ObserveAndOffloadGateProcess.php)
* [`ParallelProcess_A`](Processes/ParallelProcess_A.php)
* [`ParallelProcess_B`](Processes/ParallelProcess_B.php)
* [`ParallelProcess_C`](Processes/ParallelProcess_C.php)

It shows how to observe instances in flow, here it's offloaded branch processes using the `OffloadAnd` gate. `ObserveAndOffloadGateProcess` process creates a counter ([`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php)) and passes it through several tasks to increment it. 

> Observation in Flows does not follow [observer pattern](https://en.wikipedia.org/wiki/Observer_pattern), there is no coupling between the subject and the observer.
>
> Subjects and their observers are registered in the [`App/Config/subject-observer.php`](Config/subject-observer.php) file. It holds a map of `Subject::class => Observer::class` classes, managed by the [`Observer`](https://github.com/sousarmb/flows/blob/main/src/Observer/Kernel.php) kernel. This is a singleton service started by Flows on boot and can be gotten using the [`Observers` facade](https://github.com/sousarmb/flows/blob/main/src/Facades/Observers.php).

Everytime `LetsGoOffloadGate` class instance is used, the observer class `LetsGoOffloadGateObserver` is given access to it.

> Observation can be handled in 3 ways: real-time, deferred process or deferred flow.
> * **Realtime**: handler is called "in place" when event is sent to the manager.
> * **Deferred**:
>   * **Process**: handler is called when the process where it was sent to the manager is done.
>   * **Flow**: handler is called when the flow is done.
>
> This timing is controlled using [attributes](https://github.com/sousarmb/flows/tree/main/src/Attributes) set on the event handler class. E.g:
> * [Deferred](Events/Handlers/HelloDeferredEventHandler.php)
> * [Real-time](Events/Handlers/HelloRealtimeEventHandler.php)

Because this observer is registred as `DeferFromFlow`, when the flow is done the observer is called and passed the subject.

> `*AND` gates branch to every registered process in order of registration. When all branches are done, flow resumes at the last branching point.

> `IO` instances act as [`DTO`s](https://en.wikipedia.org/wiki/Data_transfer_object) (read-only) and are used to pass values between process tasks. `Collection` instances are allowed as well, but these are not read-only.

The offload gate branches the flow to run processes `ParallelProcess_A`, `ParallelProcess_B`, `ParallelProcess_C` in different PHP processes, giving them the same input: the output of `ObserveAndOffloadGateProcess` last task before branching. 

Once the offloaded processes are finished, they're output is grouped into a [collection](https://github.com/sousarmb/collectibles/blob/main/src/Collection.php) and the flow resumes with `ObserveAndOffloadGateProcess` next task.

> When process A uses an offload gate to branch, when processes B, C, D, ... are finished, flow always resumes in process A, at the next task.

> The output of the branched processes is always available in the task input (`$io`) to the next process int the flow. To get it, `get()` using the `[process]::class` string.

The last task dumps the results of the offloaded processes plus the counter, in a map: PID => output.
