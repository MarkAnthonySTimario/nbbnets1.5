<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropDonationTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS donation_add");
        DB::unprepared("DROP TRIGGER IF EXISTS donation_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS donation_update");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("
       
       CREATE TRIGGER `donation_update` AFTER UPDATE ON `donation`
        FOR EACH ROW BEGIN 
         
         IF NEW.donor_sn IS NULL THEN
            UPDATE donor SET donation_stat = null,donor_stat = null,deferral_basis = null WHERE seqno = OLD.donor_sn;
         END IF;
         
         IF NEW.donor_sn IS NOT NULL THEN
            SET @new_donation_stat = (IF(NEW.mh_pe_stat <> 'A' AND NEW.mh_pe_stat <> 'TD','N','Y'));
            UPDATE donor SET donation_stat = @new_donation_stat,donor_stat = NEW.mh_pe_stat, deferral_basis = NEW.mh_pe_deferral WHERE seqno = NEW.donor_sn;
       
            
            IF OLD.donor_sn <> NEW.donor_sn THEN
               SET @prev_stat_count = (SELECT count(*) FROM donor_log WHERE seqno = OLD.donor_sn AND field_name = 'donor_stat');
               IF @prev_stat_count > 0 THEN
                  SET @prev_stat = (SELECT old_value FROM donor_log WHERE seqno = OLD.donor_sn AND field_name = 'donor_stat' ORDER BY created_dt asc LIMIT 1);
                  SET @prev_dstat = (SELECT old_value FROM donor_log WHERE seqno = OLD.donor_sn AND field_name = 'donation_stat' ORDER BY created_dt asc LIMIT 1);
                  SET @prev_deferral_basis = (SELECT old_value FROM donor_log WHERE seqno = OLD.donor_sn AND field_name = 'deferral_basis' ORDER BY created_dt asc LIMIT 1);
                  UPDATE donor SET dpnation_stat = @prev_dstat,donor_stat = @prev_stat,deferral_basis = @prev_deferral_basis WHERE seqno = OLD.donor_sn;
               END IF;
               
            END IF;
         END IF;
         
         
         IF NEW.donation_stat = 'REA' THEN
            UPDATE component SET comp_stat = 'QUA' WHERE donation_id = NEW.donation_id;
            UPDATE donor SET donation_stat = 'N',donor_stat = 'PD', deferral_basis = 'TTI' WHERE seqno = NEW.donor_sn;
         END IF;
         
         
       END
       
      
       
        ");

        DB::unprepared("
        CREATE TRIGGER `donation_on_delete` AFTER DELETE ON `donation`
        FOR EACH ROW BEGIN
         
         DELETE FROM bloodproc WHERE donation_id = OLD.donation_id;
         DELETE FROM bloodtest WHERE donation_id = OLD.donation_id;
         DELETE FROM blood_typing WHERE donation_id = OLD.donation_id;
         DELETE FROM blood_discard WHERE donation_id = OLD.donation_id;
         DELETE FROM blood_label WHERE donation_id = OLD.donation_id;
       END
        ");
    }
}
