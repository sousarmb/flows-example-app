# demo-branch-99-bottles-of-beer-on-the-wall
[Script here](demo-branch-99-bottles-of-beer-on-the-wall.php)

This demo flow uses 1 process:
* [`BranchControlledLoopProcess`](Processes/BranchControlledLoopProcess.php)

It shows how to loop processes in a controlled manner using the `Fuse` and `Xor` gates. `BranchControlledLoopProcess` recreates the traditional "99 Bottles of Beer on the Wall" song. Start the process with input 99 integer:
```php
$return = $app->process(
        BranchControlledLoopProcess::class,
        new BottleCountIO(99)
);
```

The first step of the process uses a [`Fuse` gate](Processes//Gates/AnyBottlesLeftGate.php) gate to check if bottle count is valid:
* If it isn't control is handed over to the application kernel and the loop/flow stops gracefully
* If it is, the song is shown with bottle count decrements of 1.

The last step of the process is the [`Xor` gate](Processes/Gates/SingAgainGate.php) which branches the flow to `BranchControlledLoopProcess` again. 

Once the bottle count reaches zero, the fuse is blown and the flow stops as expected. Process out put is shown in the end.
