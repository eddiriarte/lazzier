Tasks
=====

This is a description of how tasks should work and which ones should be implemented.


## Functionality

All tasks should extend/implements the class/interface `App\Tasks\Task`.



A factory needs to be implemented in order to generate Task instanced from given yaml-configuration.



## To be implemented

Simple filesystem manipulations:

[x] `MakeDir` able to generate a folder on given pathname
[x] `RemoveDir`
[x] `CopyFile`
[x] `DeleteFile`
[x] `Symlink` able to set a symlink to a given target

Platform/Framework specific tasks:

[ ] `NginxRestart`
[ ] `ApacheRestart`

[ ] `ArtisanCacheClear`
[ ] `ArtisanDown`
[ ] `ArtisanUp`
[ ] `ArtisanMigrate`
[ ] `ArtisanQueueRestart`
[ ] `ArtisanCustomCommand`

[ ] `ConsoleCommand`