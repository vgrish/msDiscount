<?php
$xpdo_meta_map['msdProductGroup']= array (
  'package' => 'msdiscount',
  'version' => '1.1',
  'table' => 'ms2d_product_groups',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'discount' => '0',
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
  ),
  'composites' => 
  array (
    'Members' => 
    array (
      'class' => 'msdSaleMember',
      'local' => 'id',
      'foreign' => 'group_id',
      'owner' => 'local',
      'cardinality' => 'many',
      'criteria' => 
      array (
        'foreign' => 
        array (
          'type' => 'products',
        ),
      ),
    ),
  ),
);
