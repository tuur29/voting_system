voting_system
=============

A simple system for local votings.  
Demo: https://www.tuurlievens.net/laboratorium/vote/results

-------------

Copyright (c) 2014 Tuur Lievens  
http://www.tuurlievens.net/
Licensed under the MIT license

### HOW TO USE:
>1. Upload to server with PHP (Apache is optional, instead add ".php" to the urls in your browser)
2. change password in "options.php" and copy and change needed settings from "defaults.php"
3. Go to /admin(.php) and make a new round.
4. Go to /vote or to the root folder to vote! (NL: /stem)
5. See the results in real-time at /results (NL: /resultaten)

### HOW IT WORKS:
When you first start the script, you will have to make a new round.
The folder "active" is made in the script root directory. It contains a "round.txt", an "active.txt" and a file for every question.
The "round.txt" file is the main file and contains the time when you started the round (used to check agains double votes) and the number of options per question.

The other textfiles (1.txt, 2.txt, etc...) contain the individual votes (numbers).
When a vote (represented as a number) is cast it is added to the textfile of the corresponding question.
These numbers are parsed to the corresponding colors and plotted in a graph by "/results(.php)".
When you start a new round all people who previously voted can vote again since the timestamp is different.

When making a new round you have the option to allow step by step questioning. This means that voters can only vote up to the current active question (represented by a number in "active.txt"). When you press "Next question" on "results(.php)" the active question is increased and voters are automatically redirected to the active voting page.

Making a new round can be done by using the "Admin" button on "results(.php)" or by surfing to "/admin(.php)".
Clicking the "Disable" button removes the currently active round (including all votes). To start a new round after this you will have to log back in at "/admin(.php)". You can keep the current round by ticking "keep round".
You can also make a new active round by filling the fields and pressing "New round".
Either actions support backing up the current votes by selecting "Keep round" and giving it a name before pressing either "New round" or "Disable". These votes can be accessed by selecting the chosen name after clicking the "Round" button at "results(.php)".

The default settings can be found in "defaults.php". To change them I suggest copying them to "options.php" and editing them there. "defaults.php" is meant as a fallback in case you don't edit all options. You can find explanations for each option in the comments after each option.

### COMPATIBILITY & LANGUAGES
At the moment there is 1 additonal language option available: dutch ("nl").
You can add your own translation in "assets/language.php".
You can vote and view the results with all browsers (yes this includes IE8)!

### TODO

- BUG: using html chars in cookie not working on old android tablets?
