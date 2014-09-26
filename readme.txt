
############################################################################
#                                                                          #
#          After SNASA conquered the smoon, I present you the              #
#                                                                          #
#                            SKeyManager                                   #
#                                                                          #
# The S is abbreviated to prevent legal issues with a producer of locks,   #
# but it also disguises very good as a Winter Sports Administration Tool.  #
#                                                                          #
############################################################################

############################################################################
# Installation

 * Install software
   * Webweserver (Debian/Ubuntu: apache2 libapache2-mod-php5 php-net-ldap2 php5-ldap php5-mysql)
   * MySQL-Server
   * Git

 * Create database and grants

 * Configure apache with the sample from ./projects/apache_sample.conf
   * Add a basic auth
   * Make sure all needed rewrites are present
   * Load Modules
      * rewrite
      * ldap
      * authnz_ldap

 * Clone repo from hermssb03

 * Import initial data
   * Import the ldap users:

      cat project/ldapExport | ./project/ldap2sql.sh | mysql

   * Import locks, keys and sample permissions:

      cat project/sqlimport_0* | iconv -f ISO-8859-1 -t UTF-8 | mysql
