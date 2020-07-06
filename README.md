# timesheet-from-file-activity
derive billable hours from file activity

The inotifywait command can track file opens, writes, etc.  Based on what files are opened, I can track activity and thus billable hours for a given project.  See inotify details below.

STATUS

2020/07/06 - I have only sketched the very basic code so far.  I'm not sure when I'll commit code.  Maybe later today, maybe 6 months from now, maybe never.  I wanted to lay down the project groundwork, though.


INOTIFY DETAILS

To install:

sudo apt install inotify-tools

At least, I'm am almost certain that's the package.

The basic command:

$ inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp
Setting up watches.  Beware: since -r was given, this may take a while!
Watches established.

Note that you do not need root, at least not if the running user has permission to the relevant tree.  As for "this may take a while," I have never seen it take a while.  Usually it's essentially instant.  Maybe I've seen it take a second or two.

An example with more output:

/tmp$ mkdir blah
/tmp$ inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp/blah

Leave that window open and open another:

$ cd /tmp/blah
/tmp/blah$ ls
/tmp/blah$ 

Back in the first window:

$ inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp/blah
Setting up watches.  Beware: since -r was given, this may take a while!
Watches established.
/tmp/blah/__1594059798__OPEN,ISDIR
/tmp/blah/__1594059798__ACCESS,ISDIR
/tmp/blah/__1594059798__CLOSE_NOWRITE,CLOSE,ISDIR
