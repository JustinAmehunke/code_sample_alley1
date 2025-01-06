<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

S3 Bucket storage
--For documents
$targetDir = $isSignature ? 'documents/signatures/' : 'documents/';
--For signatures
signatures/
--For IDs
documents/

This project was built using Laravel

## Contributing

tbl_tpp_request

cover_two_surname_name
cover_two_first_name
cover_two_dob  
cover_two_telephone_number
cover_two_gender  
cover_two_relationship
cover_two_sum_assured
cover_two_premium

cover_five_surname_name
cover_five_first_name
cover_five_dob  
cover_five_telephone_number
cover_five_gender  
cover_five_relationshi
cover_five_sum_assured
cover_five_premium

cover_six_surname_name
cover_six_first_name  
cover_six_dob  
cover_six_telephone_number
cover_six_gender  
cover_six_relationship
cover_six_sum_assured
cover_six_premium

## Configuration

Alter the existing users table by running the following sql script:
ALTER TABLE tbl_users
ADD `email_verified_at` timestamp NULL DEFAULT NULL,
ADD `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
ADD `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
ADD `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
ADD `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD `current_team_id` bigint unsigned DEFAULT NULL;

Note: The rest of the database remains the same

## Data Migration

### Run this

ALTER TABLE tbl_tpp_request
ADD COLUMN beneficiary_one_gender varchar(255) NULL,
ADD COLUMN beneficiary_two_gender varchar(255) NULL,
ADD COLUMN beneficiary_three_gender varchar(255) NULL,
ADD COLUMN beneficiary_four_gender varchar(255) NULL,
ADD COLUMN beneficiary_five_gender varchar(255) NULL,
ADD COLUMN beneficiary_six_gender varchar(255) NULL,
ADD COLUMN beneficiary_seven_gender varchar(255) NULL,
ADD COLUMN beneficiary_eight_gender varchar(255) NULL,
ADD COLUMN beneficiary_nine_gender varchar(255) NULL,
ADD COLUMN beneficiary_ten_gender varchar(255) NULL;

### Or this =============================================================

### ===============================

ALTER TABLE tbl_tpp_request
ADD COLUMN IF NOT EXISTS beneficiary_one_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_two_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_three_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_four_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_five_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_six_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_seven_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_eight_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_nine_gender varchar(255) NULL,
ADD COLUMN IF NOT EXISTS beneficiary_ten_gender varchar(255) NULL;

## Migration

### Run this ======================================== Escape mode

### =================================================================================================

INSERT INTO tbl_beneficiaries (tbl_document_applications_id, full_name, dob, relationship, gender, real_relationship, percentage, id_type, id_number, phone_no, created_at, updated_at)
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_one_full_name) AS full_name,
IF(beneficiary_one_dob = '', NULL, beneficiary_one_dob) AS dob,
beneficiary_one_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_one_gender'), beneficiary_one_gender, NULL) AS gender,
beneficiary_one_real_relationship AS real_relationship,
CAST(beneficiary_one_percentage AS UNSIGNED) AS percentage,
beneficiary_one_id_type AS id_type,
beneficiary_one_id_number AS id_number,
beneficiary_one_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_two_full_name) AS full_name,
IF(beneficiary_two_dob = '', NULL, beneficiary_two_dob) AS dob,
beneficiary_two_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_two_gender'), beneficiary_two_gender, NULL) AS gender,
beneficiary_two_real_relationship AS real_relationship,
CAST(beneficiary_two_percentage AS UNSIGNED) AS percentage,
beneficiary_two_id_type AS id_type,
beneficiary_two_id_number AS id_number,
beneficiary_two_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_three_full_name) AS full_name,
IF(beneficiary_three_dob = '', NULL, beneficiary_three_dob) AS dob,
beneficiary_three_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_three_gender'), beneficiary_three_gender, NULL) AS gender,
beneficiary_three_real_relationship AS real_relationship,
CAST(beneficiary_three_percentage AS UNSIGNED) AS percentage,
beneficiary_three_id_type AS id_type,
beneficiary_three_id_number AS id_number,
beneficiary_three_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_four_full_name) AS full_name,
IF(beneficiary_four_dob = '', NULL, beneficiary_four_dob) AS dob,
beneficiary_four_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_four_gender'), beneficiary_four_gender, NULL) AS gender,
beneficiary_four_real_relationship AS real_relationship,
CAST(beneficiary_four_percentage AS UNSIGNED) AS percentage,
beneficiary_four_id_type AS id_type,
beneficiary_four_id_number AS id_number,
beneficiary_four_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_five_full_name) AS full_name,
IF(beneficiary_five_dob = '', NULL, beneficiary_five_dob) AS dob,
beneficiary_five_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_five_gender'), beneficiary_five_gender, NULL) AS gender,
beneficiary_five_real_relationship AS real_relationship,
CAST(beneficiary_five_percentage AS UNSIGNED) AS percentage,
beneficiary_five_id_type AS id_type,
beneficiary_five_id_number AS id_number,
beneficiary_five_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_six_full_name) AS full_name,
IF(beneficiary_six_dob = '', NULL, beneficiary_six_dob) AS dob,
beneficiary_six_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_six_gender'), beneficiary_six_gender, NULL) AS gender,
beneficiary_six_real_relationship AS real_relationship,
CAST(beneficiary_six_percentage AS UNSIGNED) AS percentage,
beneficiary_six_id_type AS id_type,
beneficiary_six_id_number AS id_number,
beneficiary_six_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_seven_full_name) AS full_name,
IF(beneficiary_seven_dob = '', NULL, beneficiary_seven_dob) AS dob,
beneficiary_seven_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_seven_gender'), beneficiary_seven_gender, NULL) AS gender,
beneficiary_seven_real_relationship AS real_relationship,
CAST(beneficiary_seven_percentage AS UNSIGNED) AS percentage,
beneficiary_seven_id_type AS id_type,
beneficiary_seven_id_number AS id_number,
beneficiary_seven_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_eight_full_name) AS full_name,
IF(beneficiary_eight_dob = '', NULL, beneficiary_eight_dob) AS dob,
beneficiary_eight_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_eight_gender'), beneficiary_eight_gender, NULL) AS gender,
beneficiary_eight_real_relationship AS real_relationship,
CAST(beneficiary_eight_percentage AS UNSIGNED) AS percentage,
beneficiary_eight_id_type AS id_type,
beneficiary_eight_id_number AS id_number,
beneficiary_eight_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_nine_full_name) AS full_name,
IF(beneficiary_nine_dob = '', NULL, beneficiary_nine_dob) AS dob,
beneficiary_nine_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_nine_gender'), beneficiary_nine_gender, NULL) AS gender,
beneficiary_nine_real_relationship AS real_relationship,
CAST(beneficiary_nine_percentage AS UNSIGNED) AS percentage,
beneficiary_nine_id_type AS id_type,
beneficiary_nine_id_number AS id_number,
beneficiary_nine_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_ten_full_name) AS full_name,
IF(beneficiary_ten_dob = '', NULL, beneficiary_ten_dob) AS dob,
beneficiary_ten_relationship AS relationship,
IF(COLUMN_EXISTS('tbl_tpp_request', 'beneficiary_ten_gender'), beneficiary_ten_gender, NULL) AS gender,
beneficiary_ten_real_relationship AS real_relationship,
CAST(beneficiary_ten_percentage AS UNSIGNED) AS percentage,
beneficiary_ten_id_type AS id_type,
beneficiary_ten_id_number AS id_number,
beneficiary_ten_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request;

### Or this ======================================================== Use This

### ==============================================================================================

INSERT INTO tbl_beneficiaries (tbl_document_applications_id, full_name, dob, relationship, gender, real_relationship, percentage, id_type, id_number, phone_no, created_at, updated_at)
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_one_full_name) AS full_name,
IF(beneficiary_one_dob = '', NULL, beneficiary_one_dob) AS dob,
beneficiary_one_relationship AS relationship,
beneficiary_one_gender AS gender,
beneficiary_one_real_relationship AS real_relationship,
CAST(beneficiary_one_percentage AS UNSIGNED) AS percentage,
beneficiary_one_id_type AS id_type,
beneficiary_one_id_number AS id_number,
beneficiary_one_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_two_full_name) AS full_name,
IF(beneficiary_two_dob = '', NULL, beneficiary_two_dob) AS dob,
beneficiary_two_relationship AS relationship,
beneficiary_two_gender AS gender,
beneficiary_two_real_relationship AS real_relationship,
CAST(beneficiary_two_percentage AS UNSIGNED) AS percentage,
beneficiary_two_id_type AS id_type,
beneficiary_two_id_number AS id_number,
beneficiary_two_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_three_full_name) AS full_name,
IF(beneficiary_three_dob = '', NULL, beneficiary_three_dob) AS dob,
beneficiary_three_relationship AS relationship,
beneficiary_three_gender AS gender,
beneficiary_three_real_relationship AS real_relationship,
CAST(beneficiary_three_percentage AS UNSIGNED) AS percentage,
beneficiary_three_id_type AS id_type,
beneficiary_three_id_number AS id_number,
beneficiary_three_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_four_full_name) AS full_name,
IF(beneficiary_four_dob = '', NULL, beneficiary_four_dob) AS dob,
beneficiary_four_relationship AS relationship,
beneficiary_four_gender AS gender,
beneficiary_four_real_relationship AS real_relationship,
CAST(beneficiary_four_percentage AS UNSIGNED) AS percentage,
beneficiary_four_id_type AS id_type,
beneficiary_four_id_number AS id_number,
beneficiary_four_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_five_full_name) AS full_name,
IF(beneficiary_five_dob = '', NULL, beneficiary_five_dob) AS dob,
beneficiary_five_relationship AS relationship,
beneficiary_five_gender AS gender,
beneficiary_five_real_relationship AS real_relationship,
CAST(beneficiary_five_percentage AS UNSIGNED) AS percentage,
beneficiary_five_id_type AS id_type,
beneficiary_five_id_number AS id_number,
beneficiary_five_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_six_full_name) AS full_name,
IF(beneficiary_six_dob = '', NULL, beneficiary_six_dob) AS dob,
beneficiary_six_relationship AS relationship,
beneficiary_six_gender AS gender,
beneficiary_six_real_relationship AS real_relationship,
CAST(beneficiary_six_percentage AS UNSIGNED) AS percentage,
beneficiary_six_id_type AS id_type,
beneficiary_six_id_number AS id_number,
beneficiary_six_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_seven_full_name) AS full_name,
IF(beneficiary_seven_dob = '', NULL, beneficiary_seven_dob) AS dob,
beneficiary_seven_relationship AS relationship,
beneficiary_seven_gender AS gender,
beneficiary_seven_real_relationship AS real_relationship,
CAST(beneficiary_seven_percentage AS UNSIGNED) AS percentage,
beneficiary_seven_id_type AS id_type,
beneficiary_seven_id_number AS id_number,
beneficiary_seven_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_eight_full_name) AS full_name,
IF(beneficiary_eight_dob = '', NULL, beneficiary_eight_dob) AS dob,
beneficiary_eight_relationship AS relationship,
beneficiary_eight_gender AS gender,
beneficiary_eight_real_relationship AS real_relationship,
CAST(beneficiary_eight_percentage AS UNSIGNED) AS percentage,
beneficiary_eight_id_type AS id_type,
beneficiary_eight_id_number AS id_number,
beneficiary_eight_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_nine_full_name) AS full_name,
IF(beneficiary_nine_dob = '', NULL, beneficiary_nine_dob) AS dob,
beneficiary_nine_relationship AS relationship,
beneficiary_nine_gender AS gender,
beneficiary_nine_real_relationship AS real_relationship,
CAST(beneficiary_nine_percentage AS UNSIGNED) AS percentage,
beneficiary_nine_id_type AS id_type,
beneficiary_nine_id_number AS id_number,
beneficiary_nine_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request
UNION
SELECT tbl_document_applications_id,
CONCAT_WS(' ', beneficiary_ten_full_name) AS full_name,
IF(beneficiary_ten_dob = '', NULL, beneficiary_ten_dob) AS dob,
beneficiary_ten_relationship AS relationship,
beneficiary_ten_gender AS gender,
beneficiary_ten_real_relationship AS real_relationship,
CAST(beneficiary_ten_percentage AS UNSIGNED) AS percentage,
beneficiary_ten_id_type AS id_type,
beneficiary_ten_id_number AS id_number,
beneficiary_ten_phone_no AS phone_no,
NOW() AS created_at,
NOW() AS updated_at
FROM tbl_tpp_request;

#### Covers ===============================================================

##### ==================================================================

INSERT INTO tbl_covers (
tbl_document_applications_id,
sum_assured,
premium,
cover_surname_name,
cover_first_name,
cover_dob,
cover_telephone_number,
cover_gender,
cover_relationship,
cover_sum_assured,
cover_premium,
cover_one_premium,
created_by,
modified_by,
created_at,
updated_at
)
SELECT
tbl_document_applications_id,
sum_assured,
premium,
cover_one_surname_name,
cover_one_first_name,
STR_TO_DATE(cover_one_dob, '%Y-%m-%d'), -- assuming dob is in YYYY-MM-DD format
cover_one_telephone_number,
cover_one_gender,
cover_one_relationship,
cover_one_sum_assured,
cover_one_premium,
created_by,
modified_by,
NOW(), -- or use the appropriate timestamp function
NOW() -- or use the appropriate timestamp function
FROM
tbl_tpp_request;

INSERT INTO tbl_covers (
tbl_document_applications_id,
sum_assured,
premium,
cover_surname_name,
cover_first_name,
cover_dob,
cover_telephone_number,
cover_gender,
cover_relationship,
cover_sum_assured,
cover_premium,
cover_one_premium,
created_by,
modified_by,
created_at,
updated_at
)
SELECT
tbl_document_applications_id,
sum_assured,
premium,
cover_two_surname_name,
cover_two_first_name,
STR_TO_DATE(cover_two_dob, '%Y-%m-%d'), -- assuming dob is in YYYY-MM-DD format
cover_two_telephone_number,
cover_two_gender,
cover_two_relationship,
cover_two_sum_assured,
cover_two_premium,
created_by,
modified_by,
NOW(), -- or use the appropriate timestamp function
NOW() -- or use the appropriate timestamp function
FROM
tbl_tpp_request;

-- Repeat the above INSERT statement for cover_three and cover_four data as needed

### ========================================

### Request Tables ========================================================

### ==============================================================================================

DROP TABLE IF EXISTS `tbl_claim_request_v2`;
CREATE TABLE `tbl_claim_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`claim_number` text,
`policy_number` text,
`address` text,
`mobile` text,
`email` text,
`tin` text,
`id_type` text,
`id_number` text,
`claim_type` text,
`reason_for_claim` text,
`other_reason_for_claim` text,
`payment_option` text,
`payment_method` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`payment_bank_name` text,
`account_number` text,
`account_holder_name` text,
`payment_account_number` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`payment_account_holder_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`declaration_text` text,
`my_name` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`payment_bank_branch` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`customer_name` text,
`signature_file` text,
`rec_value` text,
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_corporate_request_v2`;
CREATE TABLE `tbl_corporate_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`instut_name` text,
`rel_purpose` text,
`inc_country` text,
`income_source` text,
`business_nature` text,
`reg_number` text,
`corp_ent_tel` text,
`corp_ent_email` text,
`corp_ent_website` text,
`corp_ent_post_address` text,
`corp_ent_perm_address` text,
`first_director_fname` text,
`first_director_mname` text,
`first_director_sname` text,
`first_director_idType` text,
`first_director_idNumber` text,
`first_director_nationality` text,
`first_director_occupation` text,
`first_director_physicalAdress` text,
`first_director_dob` text,
`first_director_tel` text,
`first_director_residAtatus` text,
`second_director_fname` text,
`second_director_mname` text,
`second_director_sname` text,
`second_director_idType` text,
`second_director_idNumber` text,
`second_director_nationality` text,
`second_director_occupation` text,
`second_director_physicalAdress` text,
`second_director_dob` text,
`second_director_tel` text,
`second_director_residAtatus` text,
`beneficiary_ownership_type` text,
`beneficiary_fullname` text,
`beneficiary_dob` text,
`beneficiary_sex` text,
`beneficiary_nationality` text,
`beneficiary_idType` text,
`beneficiary_idNumber` text,
`beneficiary_tel` text,
`beneficiary_email` text,
`beneficiary_postal_address` text,
`beneficiary_residAddress` text,
`official_fname` text,
`official_mname` text,
`official_sname` text,
`official__idType` text,
`official__idNumber` text,
`official__nationality` text,
`official__dob` text,
`official_physical_address` text,
`official_tel` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`signature_option` text,
`age` text,
`signature_file` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_deathclaim_request_v2`;
CREATE TABLE `tbl_deathclaim_request_v2` (
`id` bigint unsigned NOT NULL AUTO*INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`policy_number` text,
`claim_percentage` text,
`claim_type` text,
`reason_for_claim` text,
`name_of_claimant` text,
`mobile_no` text,
`digital_address` text,
`name_of_deceased` text,
`date_of_birth_of_deceased` text,
`relationship_to_claimant` text,
`mobile_of_deceased` text,
`home_tel_of_deceased` text,
`work_tel_of_deceased` text,
`address_of_deceased` text,
`area_of_deceased` text,
`house_number_of_deceased` text,
`landmark_to_house_of_deceased` text,
`occupation_of_deceased` text,
`employer_of_deceased` text,
`employer_location_of_deceased` text,
`place_of_death` text,
`name_of_hospital` text,
`date_of_death` text,
`cause_of_death` text,
`place_of_accident` text,
`name_of_police_station` text,
`address_of_police_station` text,
`body_deposited` text,
`motuary_name` text,
`body_buried` text,
`cemetery_name` text,
`burial_date` text,
`name_of_entity_handled_burial_service` text,
`contact_details_confirmation` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`account_number` text,
`account_holder_name` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`payment_bank_branch` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`payment_method*`text,
 `payment_account_number`text,
 `payment_account_holder_name`text,
 `declaration_text`text,
 `signature_file`text,
 `rec_value`text,
  UNIQUE KEY`id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_educator_request_v2`;
CREATE TABLE `tbl_educator_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text,
`country_of_birth` text,
`nationality` text,
`marital_status` text,
`id_type` text,
`id_number` text,
`id_type_in_resident_country` text,
`id_number_in_resident_country` text,
`mobile` text,
`email` text,
`address` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`source_of_income` text,
`other_income_sources` text,
`monthly_income` text,
`annual_premium` text,
`occupation` text,
`tin` text,
`payment_term` text,
`other_payment_term` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_frequency` text,
`premium` text,
`sum_assured` text,
`payment_commencement_month` text,
`health_issues` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`is_a_smoker` text,
`with_dependants` text,
`is_politically_exposed` text,
`bntCreate` text,
`signature_file` text,
`uploaded_signature` text,
`token` text,
`payment_bank_branch` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`agent_code_or_name` text,
`agent_name` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`declaration_text` text,
`rec_value` text,
`annual_premium_update` text,
`payer_name` text,
`payer_relationship_to_policy_holder` text,
`payer_id_type` text,
`payer_id_number` text,
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_fidosip_request_v2`;
CREATE TABLE `tbl_fidosip_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text,
`nationality` text,
`country_of_birth` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`country_of_residence` text,
`marital_status` text,
`id_type` text,
`id_number` text,
`id_type_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_number_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`employer_name` text,
`occupation` text,
`empoyment_employer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_occupation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_staff_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`hidden_occupation` text,
`staff_number` text,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`address` text,
`source_of_income` text,
`monthly_income` text,
`annual_premium` text,
`other_income_sources` text,
`tin` text,
`premium` text,
`payment_frequency` text,
`sum_assured` text,
`payment_term` text,
`other_payment_term` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_commencement_month` text,
`health_issues` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`is_smoker` text,
`with_dependants` text,
`is_politically_exposed` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`signature_file` text,
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_keyman_request_v2`;
CREATE TABLE `tbl_keyman_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`country_of_birth` text,
`nationality` text,
`marital_status` text,
`is_politically_exposed` text,
`with_dependants` text,
`is_a_smoker` text,
`id_type` text,
`id_number` text,
`id_type_resident_country` text,
`id_number_not_ghana` text,
`id_type_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_number_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`country_of_residence` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`undded` text,
`address` text,
`privacy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`oversea_address` text,
`sum_assured` text,
`premium` text,
`payment_commencement_month` text,
`employer_name` text,
`occupation` text,
`staff_number` text,
`empoyment_employer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_occupation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_staff_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`source_of_income` text,
`monthly_income` text,
`other_income_sources` text,
`tin` text,
`policy_term` text,
`other_payment_term` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_frequency` text,
`health_issues` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`signature_option` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_mandate_request_v2`;
CREATE TABLE `tbl_mandate_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`agent_code` text,
`policy_holder_name` text,
`premium` text,
`payment_method` text,
`telco_name` text,
`wallet_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`wallet_number` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`agent_name` text,
`policy_no` text,
`declaration_text` text,
`sign_img` text,
`signature_file` text,
`payment_bank_branch` text,
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_personalaccident_request_v2`;
CREATE TABLE `tbl_personalaccident_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`country_of_birth` text,
`nationality` text,
`country_of_residence` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`marital_status` text,
`is_politically_exposed` text,
`with_dependants` text,
`is_a_smoker` text,
`id_type` text,
`id_number` text,
`occupation` text,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`address` text,
`address_not_ghana` text,
`in_good_health` text,
`already_have_a_personal_accident_insurance` text,
`already_have_a_personal_accident_insurance_details` text,
`already_have_a_life_insurance_with_us` text,
`past_illness` text,
`accident_prone_activities` text,
`sum_assured` text,
`net_premium` text,
`insurance_for_twelve_months` text,
`class` text,
`deduct` text,
`premium_medical_exps` text,
`sport` text,
`total_abstainer` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`bntCreate` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`signature_option` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_refund_request_v2`;
CREATE TABLE `tbl_refund_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`claim_number` text,
`policy_number` text,
`address` text,
`mobile` text,
`email` text,
`tin` text,
`id_type` text,
`id_number` text,
`claim_type` text,
`refund_type` text,
`other_reason_for_refund` text,
`source_of_payment_option` text,
`source_of_payment_bank_name` text,
`source_of_account_number` text,
`source_of_telco_name` text,
`source_of_wallet_number` text,
`source_of_wallet_name` text,
`source_of_worksite_name` text,
`source_of_staff_number` text,
`payment_option` text,
`payment_bank_name` text,
`payment_bank_branch` text,
`account_number` text,
`account_holder_name` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`declaration_text` text,
`my_name` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`signature_option` text,
`deleted` int DEFAULT '0',
`source_of_bank_branch` text,
`source_of_account_holder_name` text,
`claimpayment_payment_option` text,
`claimpayment_of_payment_bank_name` text,
`claimpayment_of_bank_branch` text,
`claimpayment_of_account_number` text,
`claimpayment_of_account_holder_name` text,
`claimpayment_of_telco_name` text,
`claimpayment_of_wallet_number` text,
`claimpayment_of_wallet_name` text,
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_sip_request_v2`;
CREATE TABLE `tbl_sip_request_v2` (
`id` bigint unsigned NOT NULL AUTO*INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text,
`nationality` text,
`country_of_residence` text,
`country_of_birth` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`marital_status` text,
`id_type` text,
`id_number` text,
`id_type_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_number_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`employer_name` text,
`empoyment_employer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_occupation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_staff_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`occupation` text,
`staff_number` text,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`address` text,
`source_of_income` text,
`monthly_income` text,
`annual_premium` text,
`other_income_sources` text,
`tin` text,
`premium` text,
`sum_assured` text,
`payment_term` text,
`other_payment_term` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`payment_frequency` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_commencement_month` text,
`health_issues` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`is_smoker` text,
`with_dependants` text,
`is_politically_exposed` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`privacy` text,
`payment_term*`text,
 `agent_code_or_name`text,
 `signature_option`text,
 `deleted`int DEFAULT '0',
 `annual_premium_update`text,
 `payer_name`text,
 `payer_relationship_to_policy_holder`text,
 `payer_id_type`text,
 `payer_id_number`text,
 `signature_file`text,
  UNIQUE KEY`id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_term_request_v2`;
CREATE TABLE `tbl_term_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`country_of_birth` text,
`nationality` text,
`marital_status` text,
`is_politically_exposed` text,
`with_dependants` text,
`is_a_smoker` text,
`id_type` text,
`id_number` text,
`id_type_resident_country` text,
`id_number_not_ghana` text,
`country_of_residence` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_type_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_number_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`undded` text,
`address` text,
`oversea_address` text,
`sum_assured` text,
`premium` text,
`payment_commencement_month` text,
`employer_name` text,
`occupation` text,
`staff_number` text,
`empoyment_employer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_occupation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_staff_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`source_of_income` text,
`monthly_income` text,
`other_income_sources` text,
`tin` text,
`policy_term` text,
`other_payment_term` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`payment_bank_branch` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_frequency` text,
`health_issues` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`signature_option` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_tpp_request_v2`;
CREATE TABLE `tbl_tpp_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text,
`country_of_birth` text,
`marital_status` text,
`id_type` text,
`id_number` text,
`nationality` text,
`tax_residence` text,
`id_type_not_ghana` text,
`id_number_not_ghana` text,
`id_type_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`id_number_in_resident_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`postal_address` text,
`address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`residential_address` text,
`source_of_income` text,
`monthly_income` text,
`other_income_sources` text,
`tin` text,
`employer_name` text,
`occupation` text,
`staff_number` text,
`empoyment_employer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_occupation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`empoyment_staff_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`payment_frequency` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`employer` text,
`staff_id` text,
`office_building_location` text,
`payment_commencement_month` text,
`sum_assured` text,
`cover_one_premium` text,
`premium` text,
`cover_id` bigint unsigned DEFAULT NULL,
`parents_alive` text,
`any_other_policy` text,
`policy_name` text,
`policy_number` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`health_issues` text,
`long_term_medication` text,
`illment_description` text,
`how_did_you_hear` text,
`agent_code` text,
`agent_name` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`is_smoker` text,
`with_dependants` text,
`is_politically_exposed` text,
`bntCreate` text,
`payment_bank_branch` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`signature_option` text,
`signature_file` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `tbl_travel_request_v2`;
CREATE TABLE `tbl_travel_request_v2` (
`id` bigint unsigned NOT NULL AUTO_INCREMENT,
`tbl_document_applications_id` int DEFAULT NULL,
`privacy` text,
`title` text,
`product_name` text,
`other_title` text,
`surname` text,
`firstname` text,
`othernames` text,
`gender` text,
`date_of_birth` text,
`age` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
`country_of_birth` text,
`nationality` text,
`is_politically_exposed` text,
`with_dependants` text,
`marital_status` text,
`id_type` text,
`id_number` text,
`id_type_in_resident_country` text,
`id_number_in_resident_country` text,
`mobile` text,
`email` text,
`client_resides_in_ghana` text,
`region_in_ghana` text,
`address` text,
`date_of_departure` text,
`date_of_return` text,
`destination` text,
`premium` text,
`occupation` text,
`payment_method` text,
`telco_name` text,
`wallet_number` text,
`wallet_name` text,
`payment_bank_name` text,
`payment_account_number` text,
`payment_account_holder_name` text,
`free_from_sickness` text,
`illment_description` text,
`trustee_full_name` text,
`trustee_dob` text,
`trustee_gender` text,
`trustee_relationship` text,
`trustee_id_type` text,
`trustee_id_number` text,
`trustee_mobile_number` text,
`how_did_you_hear` text,
`agent_code` text,
`agent_name` text,
`signopt` text,
`final_signature_base64_image_svg` text,
`beneficiary_id` bigint unsigned DEFAULT NULL,
`uploaded_signature` text,
`is_a_smoker` text,
`employer` text,
`staff_id` text,
`agent_code_or_name` text,
`declaration_text` text,
`signature_option` text,
`deleted` int DEFAULT '0',
UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

## Fields in the new tables but not in the old one

### educator

beneficiary_id
agent_name
rec_value

### sip

country_of_birth
empoyment_employer
empoyment_occupation
empoyment_staff_id
payment_term

### fidosip

country_of_birth
country_of_residence
empoyment_employer
empoyment_occupation
empoyment_staff_id
hidden_occupation
payment_term\*

### tpp

cover_id

### Travel

age
is_a_smoker

### Term

country_of_residence
empoyment_employer
empoyment_occupation
empoyment_staff_id
payment_bank_branch

### Mandate

wallet_name
is_a_smoker

### Accident

country_of_residence

### Claim

payment_method
payment_account_number
payment_account_holder_name
rec_value

### corporate

uploaded_signature
is_a_smoker
signature_option
signature_file

### keyman

age
id_type_in_resident_country
id_number_in_resident_country
privacy
empoyment_employer
empoyment_occupation
empoyment_staff_id

### deathclaim

payment*method*
payment_account_number
payment_account_holder_name
declaration_text
rec_value

### refund

source_of_bank_branch
source_of_account_holder_name
claimpayment_payment_option
claimpayment_of_payment_bank_name
claimpayment_of_bank_branch
claimpayment_of_account_number
claimpayment_of_account_holder_name
claimpayment_of_telco_name
claimpayment_of_wallet_number
claimpayment_of_wallet_name

### documents applications

terms_and_conditions
delete_product
modon
modby

ALTER TABLE tbl_document_applications ADD COLUMN flag_request INT DEFAULT 0;
ALTER TABLE tbl_document_applications ADD COLUMN flag_comment TEXT NULL;

### tbl_document_checklist

form_filled

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
