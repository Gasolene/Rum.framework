Written by Darnell S., Aug 15, 2014

Copyright 2014 by Darnell S.
Redistribution of this file is permitted under the GNU Public License.

Installation
------------------

1. Unpack the contents into a web directory
2. Map the html folder to the public root

Documentation
------------------

1. visit php-rum.com

Known Issues
------------------

1. Unit Tests can not trace App::sendHttpStatus() calls
2. ActiveRecord:: associative tables must use the same field names as the relationship tables even if mapping is manually defined
3. The following ActiveRecord methods do not work when multiple relationships to the same object exist: ActiveRecordBase::removeAssociationsByType()
