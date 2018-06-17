<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTriggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS donation_schedules_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS donor_on_update");
        DB::unprepared("DROP TRIGGER IF EXISTS donation_add");
        DB::unprepared("DROP TRIGGER IF EXISTS donation_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS donation_update");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_typing_on_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_typing_on_update");
        DB::unprepared("DROP TRIGGER IF EXISTS bloodproc_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS bloodtest_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_discard_on_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_release_batch_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_release_batch_on_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS blood_request_batch_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS bts_blood_request_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS r_code_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS r_facility_on_delete");
        DB::unprepared("DROP TRIGGER IF EXISTS r_facility_on_update");
        DB::unprepared("DROP TRIGGER IF EXISTS r_facility_upd_on_update");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("
            CREATE TRIGGER `donation_schedules_on_delete` AFTER DELETE ON `donation_schedules`
            FOR EACH ROW BEGIN
            
            DELETE FROM donation WHERE sched_id = OLD.sched_id;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `donor_on_update` AFTER UPDATE ON `donor`
                FOR EACH ROW BEGIN
                
                
                
                IF NEW.donation_stat <> OLD.donation_stat THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'donation_stat',OLD.donation_stat ,NEW.donation_stat , NEW.updated_by );
                END IF;
            
                
                IF NEW.donor_stat <> OLD.donor_stat THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'donor_stat',OLD.donor_stat ,NEW.donor_stat , NEW.updated_by );
                END IF;
                
                
                IF NEW.deferral_basis <> OLD.deferral_basis THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'deferral_basis',OLD.deferral_basis ,NEW.deferral_basis , NEW.updated_by );
                END IF;
            
            
                
                IF NEW.donor_id <> OLD.donor_id THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'donor_id',OLD.donor_id ,NEW.donor_id , NEW.updated_by );
                END IF;
                
                
                IF NEW.lname <> OLD.lname THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'lname',OLD.lname ,NEW.lname , NEW.updated_by );
                END IF;
                
                
                IF NEW.fname <> OLD.fname THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'fname',OLD.fname ,NEW.fname , NEW.updated_by );
                END IF;
                
                
                IF NEW.mname <> OLD.mname THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'mname',OLD.mname ,NEW.mname , NEW.updated_by );
                END IF;
                
                
                IF NEW.bdate <> OLD.bdate THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'bdate',OLD.bdate ,NEW.bdate , NEW.updated_by );
                END IF;
                
                
                IF NEW.gender <> OLD.gender THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'gender',OLD.gender ,NEW.gender , NEW.updated_by );
                END IF;
                
                
                IF NEW.civil_stat <> OLD.civil_stat THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'civil_stat',OLD.civil_stat ,NEW.civil_stat , NEW.updated_by );
                END IF;
                
                
                IF NEW.tel_no <> OLD.tel_no THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'tel_no',OLD.tel_no ,NEW.tel_no , NEW.updated_by );
                END IF;
                
                
                IF NEW.mobile_no <> OLD.mobile_no THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'mobile_no',OLD.mobile_no ,NEW.mobile_no, NEW.updated_by );
                END IF;
                
                
                IF NEW.email <> OLD.email THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'email',OLD.email ,NEW.email , NEW.updated_by );
                END IF;
                
                
                IF NEW.nationality <> OLD.nationality THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'nationality',OLD.nationality ,NEW.nationality , NEW.updated_by );
                END IF;
                
                
                IF NEW.occupation <> OLD.occupation THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'occupation',OLD.occupation ,NEW.occupation , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_no_st_blk <> OLD.home_no_st_blk THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_no_st_blk',OLD.home_no_st_blk ,NEW.home_no_st_blk , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_brgy <> OLD.home_brgy THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_brgy',OLD.home_brgy ,NEW.home_brgy , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_citymun <> OLD.home_citymun THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_citymun',OLD.home_citymun ,NEW.home_citymun , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_prov <> OLD.home_prov THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_prov',OLD.home_prov ,NEW.home_prov , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_region <> OLD.home_region THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_region',OLD.home_region ,NEW.home_region , NEW.updated_by );
                END IF;
                
                
                IF NEW.home_zip <> OLD.home_zip THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'home_zip',OLD.home_zip ,NEW.home_zip , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_no_st_blk <> OLD.office_no_st_blk THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_no_st_blk',OLD.office_no_st_blk ,NEW.office_no_st_blk , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_brgy <> OLD.office_brgy THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_brgy',OLD.office_brgy ,NEW.office_brgy , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_citymun <> OLD.office_citymun THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_citymun',OLD.office_citymun ,NEW.office_citymun , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_prov <> OLD.office_prov THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_prov',OLD.office_prov ,NEW.office_prov , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_region <> OLD.office_region THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_region',OLD.office_region ,NEW.office_region , NEW.updated_by );
                END IF;
                
                
                IF NEW.office_zip <> OLD.office_zip THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'office_zip',OLD.office_zip ,NEW.office_zip , NEW.updated_by );
                END IF;
                
                
                IF NEW.facility_cd <> OLD.facility_cd THEN
                INSERT INTO donor_log VALUES(NULL,OLD.seqno,Now(),'facility_cd',OLD.facility_cd ,NEW.facility_cd , NEW.updated_by );
                END IF;
                
                
            END
        ");

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

        DB::unprepared("
            CREATE TRIGGER `blood_typing_on_insert` AFTER INSERT ON `blood_typing`
            FOR EACH ROW BEGIN
                
                UPDATE component SET 
                blood_type  = NEW.blood_type 
                WHERE donation_id = NEW.donation_id;
                
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `blood_typing_on_update` AFTER UPDATE ON `blood_typing`
            FOR EACH ROW BEGIN
            
                UPDATE component SET 
                blood_type  = NEW.blood_type 
                WHERE donation_id = NEW.donation_id;
            
                SET @no_blood_type = (SELECT count(*) FROM component WHERE location = OLD.facility_cd and blood_type = OLD.blood_type);
                IF @no_blood_type = 0 THEN
                    DELETE FROM blood_stocks WHERE facility_cd = OLD.facility_cd and blood_type = OLD.blood_type; 
                END IF;
            
        
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `bloodproc_on_delete` AFTER DELETE ON `bloodproc`
            FOR EACH ROW BEGIN
            
            DELETE FROM component WHERE donation_id = OLD.donation_id; 
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `bloodtest_on_delete` AFTER DELETE ON `bloodtest`
            FOR EACH ROW BEGIN
            
            DELETE FROM bloodtest_dtls WHERE bloodtest_no = OLD.bloodtest_no AND donation_id = OLD.donation_id;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `blood_discard_on_insert` AFTER INSERT ON `blood_discard`
            FOR EACH ROW BEGIN
            
            UPDATE component SET comp_stat = 'DIS' WHERE component_cd = NEW.component_cd AND donation_id = NEW.donation_id;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `blood_release_batch_on_delete` AFTER DELETE ON `blood_release_batch`
            FOR EACH ROW BEGIN
                
                UPDATE component SET comp_stat = 'AVA' WHERE donation_id = OLD.donation_id AND component_cd = OLD.component_cd;
            UPDATE blood_request_batch_dtls SET 
                release_val = 
                (SELECT count(*) FROM blood_release_batch WHERE request_no = OLD.request_no AND component_cd = OLD.component_cd AND blood_type = OLD.blood_type)
            WHERE request_no = OLD.request_no AND component_cd = OLD.component_cd AND blood_type = OLD.blood_type;
            UPDATE blood_request_batch SET request_stat = 3 WHERE request_no = OLD.request_no;  
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `blood_release_batch_on_insert` AFTER INSERT ON `blood_release_batch`
            FOR EACH ROW BEGIN
            
            UPDATE component SET comp_stat = 'ISS' WHERE donation_id = NEW.donation_id AND component_cd = NEW.component_cd;
            UPDATE blood_request_batch_dtls SET 
            release_val = 
            (SELECT count(*) FROM blood_release_batch WHERE request_no = NEW.request_no AND component_cd = NEW.component_cd AND blood_type = NEW.blood_type)
            WHERE request_no = NEW.request_no AND component_cd = NEW.component_cd AND blood_type = NEW.blood_type;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `blood_request_batch_on_delete` AFTER DELETE ON `blood_request_batch`
            FOR EACH ROW BEGIN
            
            DELETE FROM blood_request_batch_dtls WHERE request_no = OLD.request_no;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `bts_blood_request_on_delete` AFTER DELETE ON `bts_blood_request`
            FOR EACH ROW BEGIN
            
            DELETE FROM bts_blood_request_dtls WHERE request_id = OLD.request_id AND facility_cd = OLD.facility_cd;
            
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `r_code_on_delete` AFTER DELETE ON `r_code`
            FOR EACH ROW BEGIN
            
            delete from r_codedtl where code = old.code;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `r_facility_on_delete` AFTER DELETE ON `r_facility`
            FOR EACH ROW BEGIN
            
            UPDATE r_user SET disable_flag = 'Y' WHERE facility_cd = OLD.facility_cd;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `r_facility_on_update` AFTER UPDATE ON `r_facility`
            FOR EACH ROW BEGIN
                
                IF NEW.facility_cd <> OLD.facility_cd THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'facility_cd',OLD.facility_cd ,NEW.facility_cd,NEW.updated_by);
                END IF;
                
                
                IF NEW.facility_name <> OLD.facility_name THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'facility_name',OLD.facility_name ,NEW.facility_name ,NEW.updated_by);
                END IF;
                
                
                IF NEW.type <> OLD.type THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'type',OLD.type ,NEW.type ,NEW.updated_by);
                END IF;
                
                
                IF NEW.category <> OLD.category THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'category',OLD.category ,NEW.category ,NEW.updated_by);
                END IF;
                
                
                IF NEW.class <> OLD.class THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'class',OLD.class ,NEW.class ,NEW.updated_by);
                END IF;
                
                
                IF NEW.owner_name <> OLD.owner_name THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'owner_name',OLD.owner_name ,NEW.owner_name ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_no_st_blk <> OLD.address_no_st_blk THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_no_st_blk',OLD.address_no_st_blk ,NEW.address_no_st_blk ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_bgy <> OLD.address_bgy THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_bgy',OLD.address_bgy ,NEW.address_bgy ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_citymun <> OLD.address_citymun THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_citymun',OLD.address_citymun ,NEW.address_citymun ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_prov <> OLD.address_prov THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_prov',OLD.address_prov ,NEW.address_prov ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_region <> OLD.address_region THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_region',OLD.address_region ,NEW.address_region ,NEW.updated_by);
                END IF;
                
                
                IF NEW.address_zip <> OLD.address_zip THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'address_zip',OLD.address_zip ,NEW.address_zip ,NEW.updated_by);
                END IF;
                
                
                IF NEW.tel_no <> OLD.tel_no THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'tel_no',OLD.tel_no ,NEW.tel_no ,NEW.updated_by);
                END IF;
                
                
                IF NEW.mobile_no <> OLD.mobile_no THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'mobile_no',OLD.mobile_no ,NEW.mobile_no ,NEW.updated_by);
                END IF;
                
                
                IF NEW.fax <> OLD.fax THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'fax',OLD.fax ,NEW.fax ,NEW.updated_by);
                END IF;
                
                
                IF NEW.email <> OLD.email THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'email',OLD.email ,NEW.email ,NEW.updated_by);
                END IF;
                
                
                IF NEW.contact_user_id <> OLD.contact_user_id THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'contact_user_id',OLD.contact_user_id ,NEW.contact_user_id ,NEW.updated_by);
                END IF;
                
                
                IF NEW.contact_name <> OLD.contact_name THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'contact_name',OLD.contact_name ,NEW.contact_name ,NEW.updated_by);
                END IF;
                
                
                IF NEW.designation <> OLD.designation THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'designation',OLD.designation ,NEW.designation ,NEW.updated_by);
                END IF;
                
                
                IF NEW.bsf_av <> OLD.bsf_av THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'bsf_av',OLD.bsf_av ,NEW.bsf_av ,NEW.updated_by);
                END IF;
                
                
                IF NEW.max_donor_age <> OLD.max_donor_age THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'max_donor_age',OLD.max_donor_age ,NEW.max_donor_age ,NEW.updated_by);
                END IF;
                
                
                IF NEW.min_donor_age <> OLD.min_donor_age THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'min_donor_age',OLD.min_donor_age ,NEW.min_donor_age ,NEW.updated_by);
                END IF;
                
                
                IF NEW.no_months_to_nxt_don <> OLD.no_months_to_nxt_don THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'no_months_to_nxt_don',OLD.no_months_to_nxt_don ,NEW.no_months_to_nxt_don ,NEW.updated_by);
                END IF;
                
                
                IF NEW.res_hrs <> OLD.res_hrs THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'res_hrs',OLD.res_hrs ,NEW.res_hrs ,NEW.updated_by);
                END IF;
            
                
                IF NEW.no_days_expire_warning <> OLD.no_days_expire_warning THEN
                INSERT INTO r_facility_log VALUES(NULL,OLD.facility_cd,Now(),'no_days_expire_warning',OLD.no_days_expire_warning ,NEW.no_days_expire_warning ,NEW.updated_by);
                END IF;
                    
            END
        ");

        DB::unprepared("
            CREATE TRIGGER `r_facility_upd_on_update` AFTER UPDATE ON `r_facility_upd`
            FOR EACH ROW BEGIN
            
            IF NEW.disable_flg = 'N' THEN
                UPDATE r_facility SET 
                `bsf_av` = NEW.bsf_av, 
                `max_donor_age` = NEW.max_donor_age, 
                `min_donor_age` = NEW.min_donor_age, 
                `no_months_to_nxt_don` = NEW.no_months_to_nxt_don,  
                `res_hrs` = NEW.res_hrs,
                `no_days_expire_warning` = NEW.no_days_expire_warning 
                WHERE facility_cd = NEW.facility_cd;
            END IF;
            END
        ");
    }
}
