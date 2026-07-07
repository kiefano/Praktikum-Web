<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExtraFieldsToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'ppn' => [
                'type'    => 'DOUBLE',
                'null'    => TRUE,
                'default' => 0,
            ],
            'biaya_admin' => [
                'type'    => 'DOUBLE',
                'null'    => TRUE,
                'default' => 0,
            ],
            'kupon_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => TRUE,
                'default'    => NULL,
            ],
            'diskon_kupon' => [
                'type'    => 'DOUBLE',
                'null'    => TRUE,
                'default' => 0,
            ],
        ];

        if (!$this->db->fieldExists('ppn', 'transaction')) {
            $this->forge->addColumn('transaction', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['ppn', 'biaya_admin', 'kupon_code', 'diskon_kupon']);
    }
}