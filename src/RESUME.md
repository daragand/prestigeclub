The majority of athletes have aspired to attain stardom at some point in their lives. This is what offer Prestige Club, one client of Duneoo. Prestige Club is a group of people who take pictures and plan events in sports clubs. The goal is to take pictures of the members during their sport session and sell them to their families.
To optimize their service, I proposed to the association an application. The main goal is to provide a web application to manage the pictures in order to save time. 

The application is developed with the Symfony framework, and it uses the ORM Doctrine and MySQL.
 The project is divided in three users experience : the administration (photograph), the club's Administration, and the parent's part. 
To administrate the application, i used EasyAdmin Bundle, a bundle which provides a back office easy to use. Moreover, i installed Vich Uploader,  which enable to upload pictures and change the name for a unique name. 
To manage the parents part (clients), i used twig, Bootstrap and a template. To display the photos, i used the  LiipImagineBundle bundle which enable to resize the pictures, encode the picture in the format webp, then apply a watermark.
To finish, i used the payment service Stripe. 
A few JavaScript's code have been exploited to check the action of users.
This project is developed to change the organization of the association and help Duneoo to forgot WordPress to sell the photos online.

