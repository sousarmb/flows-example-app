# demo-event-deferred-and-realtime
[Script here](demo-event-deferred-and-realtime.php)

This demo flow uses 1 process:
* [`DeferredAndRealtimeEventsProcess`](Processes/DeferredAndRealtimeEventsProcess.php)

It shows how to use `realtime` and `deferred` event handling with Flows. 

> Events and their handlers are registered in the [`App/Config/event-handler.php`](Config/event-handler.php) file. It holds a map of `Event::class => Handler::class` classes, managed by the [`Event`](https://github.com/sousarmb/flows/blob/main/src/Event/Kernel.php) kernel. This is a singleton service started by Flows on boot and can be gotten using the [`Events` facade](https://github.com/sousarmb/flows/blob/main/src/Facades/Events.php).

`DeferredAndRealtimeEventsProcess` first task creates a counter that is passed to the next tasks, then uses the events facade to trigger a deferred event handler. The second task increments the counter and triggers a realtime event handler.

The result can be seen on the script output: the realtime handler message is output before the end of the process, when the flow is over the deferred one.

> Events can be handled in 3 ways: real-time, deferred process or deferred flow.
> * **Realtime**: handler is called "in place" when event is sent to the manager.
> * **Deferred**:
>   * **Process**: handler is called when the process where it was sent to the manager is done.
>   * **Flow**: handler is called when the flow is done.
>
> This timing is controlled using [attributes](https://github.com/sousarmb/flows/tree/main/src/Attributes) set on the event handler class. E.g:
> * [Deferred](Events/Handlers/HelloDeferredEventHandler.php)
> * [Real-time](Events/Handlers/HelloRealtimeEventHandler.php)

> [`Event`](https://github.com/sousarmb/flows/blob/main/src/Event/Event.php) classes are basically [`DTO`](https://en.wikipedia.org/wiki/Data_transfer_object)s to be used as input to event handlers (... they extend [`IO`](https://github.com/sousarmb/collectibles/blob/main/src/IO.php) for that reason).