# timesheet-from-file-activity
derive billable hours from file activity

The inotifywait command can track file opens, writes, etc.  Based on what files are opened, I can track activity and thus billable hours for a given project.

The basic command:

$ inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp
Setting up watches.  Beware: since -r was given, this may take a while!
Watches established.

Note that you do not need root, at least not if the running user has permission to the relevant tree.  As for "this may take a while," I have never seen it take a while.  Usually it's essentially instant.  Maybe I've seen it take a second or two.

An example with more output:

/tmp$ mkdir blah
/tmp$ inotifywait -m -r --format %w%f__%T__%e --timefmt %s /tmp/blah

Leave that window open and open another:




