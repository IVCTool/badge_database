/* This file holds various queries for testing or for 
    powering functions in the interface. It is not meant to
    be run by itself.  It's more of a note pad. */

/* This query is for getting the info needed for a select box of ir requirements */
/* This version was for testing */
SELECT * FROM (wp_badgedb_requirements JOIN wp_badgedb_abstracttcs_has_requirements on wp_badgedb_requirements.id=wp_badgedb_abstracttcs_has_requirements.requirements_id) where abstracttcs_id = 14
/* This version is pared down to what we actually need to populate all the possible options */
SELECT identifier, requirements_id FROM wp_badgedb_requirements
/* This one gives all the ones that should be selected */
SELECT identifier, requirements_id FROM (wp_badgedb_requirements JOIN wp_badgedb_abstracttcs_has_requirements on wp_badgedb_requirements.id=wp_badgedb_abstracttcs_has_requirements.requirements_id) where abstracttcs_id = 14