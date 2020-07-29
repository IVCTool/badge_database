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

/* This query is for getting the info needed for a select box of badge dependancies */
/* For testing the join */
SELECT * FROM (wp_badgedb_badges JOIN wp_badgedb_badges_has_badges on wp_badgedb_badges.id=wp_badgedb_badges_has_badges.badges_id_dependency)
/* for getting rid of the badge and atcs files */
SELECT wpid FROM wp_badgedb_badges UNION SELECT wpid FROM wp_badgedb_abstracttcs

/* These queries are for the public display of badges */
SELECT * FROM wp_badgedb_badges WHERE id=?  /* Badge data */
/* All the badges it bepends on */
SELECT id, description, wpid, identifier FROM (wp_badgedb_badges_has_badges JOIN wp_badgedb_badges ON wp_badgedb_badges_has_badges.badges_id_dependency=wp_badgedb_badges.id) WHERE badges_id=21
/* All the requirements it has, including the ones form other badges if any */
CREATE TEMPORARY TABLE allbadges SELECT id FROM (wp_badgedb_badges_has_badges JOIN wp_badgedb_badges ON wp_badgedb_badges_has_badges.badges_id_dependency=wp_badgedb_badges.id) WHERE badges_id=21;
INSERT INTO allbadges (id) VALUES (21);
CREATE TEMPORARY TABLE allreqs SELECT requirements_id FROM (wp_badgedb_badges_has_requirements JOIN allbadges ON allbadges.id=wp_badgedb_badges_has_requirements.badges_id);
SELECT id, identifier, description FROM (allreqs JOIN wp_badgedb_requirements ON allreqs.requirements_id=wp_badgedb_requirements.id);
/* The graphic file. */
SELECT wp_posts.ID, wp_posts.guid FROM (wp_posts JOIN wp_badgedb_badges ON wp_posts.id=wp_badgedb_badges.wpid) WHERE wp_badgedb_badges.id=26
/* The Abstract Test Cases that could apply */
CREATE TEMPORARY TABLE allbadges SELECT id FROM (wp_badgedb_badges_has_badges JOIN wp_badgedb_badges ON wp_badgedb_badges_has_badges.badges_id_dependency=wp_badgedb_badges.id) WHERE badges_id=21;
INSERT INTO allbadges (id) VALUES (21);
CREATE TEMPORARY TABLE allreqs SELECT requirements_id FROM (wp_badgedb_badges_has_requirements JOIN allbadges ON allbadges.id=wp_badgedb_badges_has_requirements.badges_id);
CREATE TEMPORARY TABLE allatcs SELECT abstracttcs_id FROM (wp_badgedb_abstracttcs_has_requirements JOIN allreqs ON wp_badgedb_abstracttcs_has_requirements.requirements_id=allreqs.requirements_id);
SELECT id, identifier, name, description, version, wpid FROM (wp_badgedb_abstracttcs JOIN allatcs ON wp_badgedb_abstracttcs.id=allatcs.abstracttcs_id);
/* The executable test cases that apply */
/* The IVCT file info */
