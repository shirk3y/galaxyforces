<?php

require('include/header.php');

tablebegin($Lang['Crew'], 600);

?>
<textarea cols="100" rows="30" style="font-family: Terminal, Lucida Sans Unicode, Courier New; font-size: 120%">
<?php
echo htmlspecialchars(@file_get_contents("CREW.txt"));
?>
</textarea>
<?php

tableend('<a href="control.php">' . $Lang['GoBack'] . ' &gt;&gt;</a>');

require('include/footer.php');
