Prestige Club, one client of Duneoo, is an association of photographer which organizes events in sport Clubs. The goal is to take pictures of the members during their sport session and to sell them at the relatives. I was in charge of the application development. The main goal is to provide a web application to manage the pictures in order to save time. 

The application is developed with the Symfony framework, use the ORM Doctrine and MySQL.
 The project is divided in tree parts : the administration (photographer), the club's Administration, and the parent's part. 
To administrate the application, i used EasyAdmin Bundle, a bundle which provides a back office easy to use. Moreover, i install Vich Uploader, a bundle which enable to upload pictures and change the name. 
To manage the parents part, i used twig, BootStrap and a template. To display the pictures, i used a bundle called LiipImagineBundle which enable to resize the pictures, encode picture in the format webp, then apply a watermark.
To finish, i used the payment service Stripe.