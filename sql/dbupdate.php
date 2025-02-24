<#1>
    <?php

        // Database update script for plugin
        $fields = array(
            'id' => array(
                'type' => 'integer',
                'length' => 4,
                'notnull' => true
            ),
            'protocol' => array(
                'type' => 'text',
                'length' => 10,
                'notnull' => true
            ),
            'domain' => array(
                'type' => 'text',
                'length' => 255,
                'notnull' => true
            ),
            'port' => array(
                'type' => 'integer',
                'length' => 4,
                'notnull' => false
            ),
            'path' => array(
                'type' => 'text',
                'length' => 255,
                'notnull' => false
            ),
            'color' => array(
                'type' => 'text',
                'length' => 20,
                'notnull' => true
            )
        );

        // Check if table already exists
        if (!$ilDB->tableExists("uihk_url_display")) {
            $ilDB->createTable("uihk_url_display", $fields);
            $ilDB->addPrimaryKey("uihk_url_display", array("id"));
        }

        // Check if sequence already exists before creating it
        if (!$ilDB->sequenceExists("uihk_url_display")) {
            $ilDB->createSequence("uihk_url_display");
        }
    ?>