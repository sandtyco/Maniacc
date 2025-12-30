<div class="sidebar">
    <h3>[ NAVIGATION ]</h3>
    <ul>
        <?php 
            $pg = basename($_SERVER['PHP_SELF']); 
        ?>

        <li>
            <a href="dash.php" class="<?= ($pg == 'dash.php') ? 'active' : '' ?>">
                > DASHBOARD
            </a>
        </li>
        <li>
            <a href="acc_list.php" class="<?= ($pg == 'acc_list.php') ? 'active' : '' ?>">
                > ACC LIST
            </a>
        </li>
        <li>
            <a href="acc_add.php" class="<?= ($pg == 'acc_add.php') ? 'active' : '' ?>">
                > ACC ENTRY
            </a>
        </li>

        <li>
            <a href="acc_chg.php" class="<?= ($pg == 'acc_chg.php') ? 'active' : '' ?>">
                > CHG PASS
            </a>
        </li>
        
        <hr style="border-color: #004400; margin: 15px 0;">
        
        <li>
            <a href="../logout.php" style="color: #005500;">
                > DISCONNECT
            </a>
        </li>
    </ul>
    <hr style="border-color: #004400; margin: 15px 0;">
    <img src="https://media.tenor.com/AAX7VrZGt18AAAAM/matrix-neo.gif" width="210px">
</div>