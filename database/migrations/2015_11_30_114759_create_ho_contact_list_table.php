<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoContactListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Contact HawaiiOption
         * Table: ho_contact_list
            +----------------------------------+---------------------------+------+-----+---------+----------------+
            | Field                            | Type                      | Null | Key | Default | Extra          |
            +----------------------------------+---------------------------+------+-----+---------+----------------+
            | id                               | bigint(20) unsigned       | NO   | PRI | NULL    | auto_increment |
            | type                             | enum('all','book','tour') | NO   |     | NULL    |                |
            | send_to                          | enum('jhi','user')        | NO   |     | NULL    |                |
            | contents                         | text                      | NO   |     | NULL    |                |
            | email                            | varchar(250)              | NO   |     | NULL    |                |
            | name                             | varchar(50)               | NO   |     | NULL    |                |
            | start_id                         | bigint(20) unsigned       | YES  |     | NULL    |                |
            | created_at_jp                    | datetime                  | NO   |     | NULL    |                |
            | updated_at_jp                    | datetime                  | NO   |     | NULL    |                |
            | created_at_hi                    | datetime                  | NO   |     | NULL    |                |
            | updated_at_hi                    | datetime                  | NO   |     | NULL    |                |
            | lastupdate_ss_account_id         | bigint(20) unsigned       | YES  |     | NULL    |                |
            | lastupdate_ss_terminal_master_id | bigint(20) unsigned       | YES  |     | 0       |                |
            +----------------------------------+---------------------------+------+-----+---------+----------------+
         * 
         * @author AnPCD
         * @version 2015/11/30 10:36:13
         */
        
        // Checking For Table existence
        if (!Schema::hasTable('ho_contact_list')) {
            // To create a new database table
            Schema::create('ho_contact_list', function (Blueprint $table) {
                // To set the storage engine for table
                $table->engine = 'InnoDB';
                
                // ---------------- Available Column Types ---------------- //
                $table->bigIncrements('id');
                $table->enum('type', ['all','book','tour']);
                $table->enum('send_to', ['jhi','user']);
                $table->text('contents');
                $table->string('email', 250);
                $table->string('name', 50);
                $table->bigInteger('start_id')->unsigned()->nullable();
                $table->datetime('created_at_jp');
                $table->datetime('updated_at_jp');
                $table->datetime('created_at_hi');
                $table->datetime('updated_at_hi');
                $table->bigInteger('lastupdate_ss_account_id')->unsigned()->nullable();
                $table->bigInteger('lastupdate_ss_terminal_master_id')->unsigned()->default(0)->nullable();

                // ---------------- Available Index Types ---------------- //
                // Add a unique index.
                // Add a basic index.
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // To drop an existing table
        Schema::dropIfExists('ho_contact_list');
    }
}
