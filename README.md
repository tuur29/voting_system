voting_system
=============

A simple system for local votings

HOW TO USE:
  1. Upload to server with PHP (Apache is optional, add ".php" to the urls)
  2. Configure options.php
  3. Go to /reset and make a new round.
  4. Go to /vote to vote! (or root)
  5. See the results in real-time at /results (on another device perhaps?)
  
HOW IT WORKS:
  When you first start the script, it will ask you to make a new session.
  A folder is made in the same directory. It contains a round.txt file and a bunch of other files.
  The round.txt file is the main file and contains the time when you
  started the session (used to check agains double votes) and the number of votes per question.
  
  The other textfiles (1.txt, 2.txt, etc...) contain the individual votes (numbers).
  When you vote on a question a number is added to corresponding textfile.
  These numbers are parsed to the corresponding colors by results.php. When you start a new session
  all people who previously voted can vote again since the timestamp is different.
  
  You can save a session by changing the $folder parameter in options.php to something else.
  If you want to continue the session, revert the parameter to the corresponding folder.
  
  To disable the script go to results.php and press "reset" at the bottom of the page.
  Fill in the password (leave the rest empty) and press "Disable". To reset the script,
  fill all options and press "Reset".
  