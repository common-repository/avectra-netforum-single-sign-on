<h2>netFORUM Plugin Help</h2>
<h4> For more information contact help@fusionspan.com</h4>
<div>
    <h3 style="text-decoration:underline;">netFORUM SSO Plugin</h3>
    <h4>General</h4>

    <p>The general page contains the settings to connect to netFORUM. For plugin to work, you need to put the <b>xWeb
            WSDL Url</b>, <b>xWeb Username</b> and the <b>xWeb Password</b> in their respective boxes. This information
        should be obtained from netFORUM</p>

    <p>In the <b>Connection</b> subsection, you can set the <b>Timeout</b> to how long you want the plugin to wait in
        seconds after sending a query to netFORUM for a response. Addtionally you can set the <b>Connection Timeout</b>
        to how long you want the plugin to wait in seconds after initially trying to connect to netFORUM, before any
        queries are sent.</p>

    <h4>Cache</h4>

    <p>The cache stores previous netFORUM requests</p>

    <p>Under the <b>Cache</b> tab there is a field called <b>Cache Secret Key</b> which determines the key that the
        cache is encrypted with. By default it is pre-populated with random values, you may change this value if you
        want to encrypt the cache with another secret key.<br> The <b>Cache TTL</b> determines how long you want the
        cache to persist in memory (in seconds). By default it is set to 86400 seconds (1 day)</p>
</div>
<?php
	if(!is_plugin_active("netgroups/netgroups.php")){
		echo '<div style="display:none">';
}else{
echo '
<div>';
    }


    ?>
    <h3 style="text-decoration:underline;">netFORUM Groups Plugin</h3>

    <p>The groups plugin adds two new tabs to the fusionSpan menu <b>Groups Sync</b> and <b>Groups Capabilities</b></p>

    <h4>Groups Sync</h4>

    <p>
        Under the <b>Groups Sync</b> tab you can check the <b>Receives Benefits</b> box if you want users to be placed
        in a separate role based on whether they receives benefits or not. If this box is checked they will be placed in
        roles based on the memeber type code in netFORUM. If no member type code is defined, and a user recives
        benefits, they will be placed in the role defined by the <b>Group Name</b> field.<br><br>
        If you want users who are members to be placed in a separate wordpress role, check the <b>Member Flag</b>
        checkbox. The additional options under this checkbox give you further options on when to add a member to the
        Member Flag group.
    </p>


</div>