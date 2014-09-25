
############################################################################
#                                                                          #
#          After SNASA conquered the smoon I present you the               #
#                                                                          #
#                            skeymanager                                   #
#                                                                          #
# The s is abbreviated to prevent legal problems with a producer of locks, #
#  but it also disguises very good as Winter Sports Administration Tool.   #
#                                                                          #
############################################################################

############################################################################
# Installation

 * Create database and grants

 * Configure apache with the sample from ./projects/apache_sample.conf
   * Make sure there is a basic auth
   * Make sure all needed rewrites (and its modul) is present

 * Clone repo from hermssb03

 * Import initial data
   * Import the ldap users:

      cat project/ldapExport | ./project/ldap2sql.sh | mysql

   * Import locks, keys and sample permissions:

      cat project/sqlimport_0* | iconv -f ISO-8859-1 -t UTF-8 | mysql 


############################################################################
# Development

php vendor/bin/phpmd htdocs/ html cleancode,codesize,controversial,design,naming,unusedcode --reportfile /var/www/phpmd.html
