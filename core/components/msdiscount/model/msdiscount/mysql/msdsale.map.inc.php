<?php
$xpdo_meta_map['msdSale']= array (
  'package' => 'msdiscount',
  'version' => '1.1',
  'table' => 'ms2d_sales',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'discount' => '0',
    'name' => '',
    'description' => '',
    'starts' => '0000-00-00 00:00:00',
    'ends' => '0000-00-00 00:00:00',
    'active' => 1,
    'resource' => 0,
    'image' => '',
  ),
  'fieldMeta' => 
  array (
    'discount' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '10',
      'phptype' => 'string',
      'null' => true,
      'default' => '0',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'text',
      'null' => true,
      'default' => '',
    ),
    'starts' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'sting',
      'null' => true,
      'default' => '0000-00-00 00:00:00',
    ),
    'ends' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'sting',
      'null' => true,
      'default' => '0000-00-00 00:00:00',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 1,
    ),
    'resource' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'image' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'discount' => 
    array (
      'alias' => 'discount',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'discount' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'name' => 
    array (
      'alias' => 'name',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'name' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'resource' => 
    array (
      'alias' => 'resource',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resource' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Members' => 
    array (
      'class' => 'msdSaleMember',
      'local' => 'id',
      'foreign' => 'sale_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
