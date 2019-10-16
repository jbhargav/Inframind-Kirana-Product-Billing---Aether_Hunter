# Inframind-Kirana-Product-Billing---Aether_Hunter
Web application which allows automatic detection of product using a camera. The detection of the product must be with respect to the size of the product, type of product and automatically take the cost of the product to make a bill of materials at checkout.
# Our Solution Approch
1.Retailer Local Server  :
	It is a web server which takes a snap of the products and stores the image and then it sends the image process request to the ML Model.
2.Detection :
It takes the image process request from the web application and pre-processes the image by converting to a numpy array of pixels. Simultaneously model and labels are loaded. And the image array is sent to the model then a TensorFlow session is initialized, the image is processed and returns the names with sizes of the products as a response to the webserver.
3.Response :
	It takes the Response from the model, then based on these it will classify the product names and quantities, calculates the cost and returns the bill.
  

