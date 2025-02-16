

Users of the application
--------------------------

Tmmms - add products [administrators]
Refinery - update price
Marketer - purchase products from refinery
Transporter - lift products
Driver - transport products in truck
customer - purchase products from marketers

Register Marketers
Marketers - purchase product
    - upload payment proofs
        - bank with bank details






Enums:
    - roles
        - case AdministratorRole = 'administrator'
        - case RefineryRole = 'refinery'
        - case MarketerRole = 'marketer'
        - case TransporterRole = 'transporter'
        - case DriverRole = 'driver'
        - case CustomerRole = 'customer'

    - permissions

    - AdministratorRoleEnum
        - case HumanResource = 'human resource'
        - case DataAnalyst = 'data analyst'
        - case CustomerService = 'customer service'

    - RefineryRoleEnum
        - case HumanResource = 'human resource'
        - case Manager = 'manager'
        - case CustomerService = 'customer service'

    - MarketersRoleEnum
        - case HumanResource = 'human resource'
        - case Manager = 'manager'
        - case CustomerService = 'customer service'

    - TransportersRoleEnum
        - case HumanResource = 'human resource'
        - case Manager = 'manager'
        - case CustomerService = 'customer service'





Note: 
    There will be a monthly subscription
    a monthly report of products, purchases, programs, trucks sales and more: sent to email addresses.




Dev:
    New laravel app : tmmmsapp
    Add package for web and api
    