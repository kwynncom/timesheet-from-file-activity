# timesheet-from-file-activity
derive billable hours from file activity

The inotifywait command can track file opens, writes, etc.  Based on what files are opened, I can track activity and thus billable hours for a given project.  See inotify details below.

STATUS

4:51pm 

On third thought, it may be an issue, but I'm not sure it's something to solve given that the live system won't run in NetBeans / xdebug.


4:42pm

On second thought, maybe there isn't a problem.  My destructor seems to work.  


2020/07/07 4:27pm my time (defined below, usually)

I'm writing to a file rather than MongoDB.  I've set limits on that file.  

My next task is to fix the situation where I kill the debugger in NetBeans but inotifywait doesn't stop.


It writes data to MongoDB.  I'm having trouble with scale, though.

INOTIFY LIMITS

An error: Failed to watch /home/.../; upper limit on inotify watches reached!

See: https://github.com/guard/listen/wiki/Increasing-the-amount-of-inotify-watchers

This does indeed temporarily / experimentally solve the problem:

sudo sysctl fs.inotify.max_user_watches=524288
fs.inotify.max_user_watches = 524288
sudo sysctl -p


THAT BLOODY GIT CREATION / CONNECTION COMMAND:

git remote set-url origin git@github.com:kwynncom timesheet-from-file-activity.git

Bloody as in I keep forgetting it and can't find it.  I need to proverbially tattoo it.  I suppose I should put it 
in a GitHub or something.

UPDATES

7:57pm

I'll probably get rid of the original / first working files.
Getting rid of strtotimeRecent() tests


same day 7:18pm EDT GMT -4 New York, Atlanta

I have something working.


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
