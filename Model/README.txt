Author: Ashley Banks
Title: Mini-Framework

This little framework follows the MVC (Model, View, Controller) Framework,
Also the Zend Framework coding standards. 

The Models avaliable ins this Framework are all editable to suit the project.
Each class is has commonly used methods across projects such as; Pagination, Validation, Mailing and SQL Database Handler.

This Framework uses Mustache as it's main templating system. 
Each class is created so that the properties affect the tags within a HTML template.

Every class somehow extends Handler - as this class handles the Database.
Handle extends Mustache so every class can render a template.

