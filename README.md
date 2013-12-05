voting_system
=============

A simple system for local votings

HOW TO USE:
  1. Upload to server with PHP (Apache is optional, add ".php" to the urls)
  2. Configure options.php
  3. Go to /results and make a new session.
  4. Go to /vote to vote! (or root)
  5. See the results in real-time at /results

HOW IT WORKS:
  When you first start the script, it will ask you to make a new session.
  A textfile is made in the same directory. It contains the time when you
  started the session (used to check agains double votes) and the individual votes (numbers).
  These numbers are parsed to the corresponding colors by results.php. When you start a new session
  all people who previously voted can vote again since the timestamp is different.
  
  You can save a session by changing the $file parameter in options.php to something else.
  If you want to continue the session, revert the parameter to the corresponding textfile.
  
  To disable the script go to results.php and press "reset" at the bottom of the page.
  Fill in the password (leave number of choices empty) and press "Disable". To reset the script,
  fill in number of choices and press "Reset".
