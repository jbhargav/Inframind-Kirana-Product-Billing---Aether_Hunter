# Inframind-Kirana-Product-Billing---Aether_Hunter
Web application which allows automatic detection of product using a camera. The detection of the product must be with respect to the size of the product, type of product and automatically take the cost of the product to make a bill of materials at checkout.

# Our Solution Approch

## 1. Retailer Local Server:  
It is a web server which takes a snap of the products and stores the image and then it sends the image process request to the ML Model.
## 2. Detection :
It takes the image process request from the web application and pre-processes the image by converting to a numpy array of pixels. Simultaneously model and labels are loaded. And the image array is sent to the model then a TensorFlow session is initialized, the image is processed and returns the names with sizes of the products as a response to the webserver.
## 3. Response :
It takes the Response from the model, then based on these it will classify the product names and quantities, calculates the cost and returns the bill.

# Architecture
<p align="center">
  <img src="https://imgur.com/download/419Az78/">
</p>

# Flowchart
<p align="center">
  <img src="https://imgur.com/download/JKHWHD7">
</p>

### Prerequisites
<ul>
<li>2 GB Graphics (Nvidia)</li>
<li>8 GB RAM</li>
<li>50 GB Harddisk</li>
<li>Web Camera</li>
<li>Windows 10 OS</li>
<li>Anaconda</li>
<li>Cuda</li>
<li>cuDNN</li>
<li>Python IDLE</li>
<li>LabelImg</li>
<li>Tensorflow</li>
<li>Images Data-Set</li>
</ul>

# Explaination and Procedure
First we train the model using Tensorflow Object Detection API with Faster RCNN Inception Model.
All the Training files are in <b>Model_Training</b> Directory.
## Training PART
Download the Faster-RCNN-Inception-V2-COCO model with API Files in Tensorflow Object_detection Folder

<p align="center">
  <img src="https://imgur.com/download/SWQy4Sa/">
</p>

### Setting up new Anaconda virtual environment
In Command Prompt we run the following command:
```
C:\> conda create -n tensorflow1 pip python=3.5
```
Then, activate the environment and update pip by issuing:
```
C:\> activate tensorflow1

(tensorflow1) C:\>python -m pip install --upgrade pip
```
Install tensorflow-gpu in this environment by issuing:
```
(tensorflow1) C:\> pip install --ignore-installed --upgrade tensorflow-gpu
```
Install the other necessary packages by issuing the following commands:
```
(tensorflow1) C:\> conda install -c anaconda protobuf
(tensorflow1) C:\> pip install pillow
(tensorflow1) C:\> pip install lxml
(tensorflow1) C:\> pip install Cython
(tensorflow1) C:\> pip install contextlib2
(tensorflow1) C:\> pip install jupyter
(tensorflow1) C:\> pip install matplotlib
(tensorflow1) C:\> pip install pandas
(tensorflow1) C:\> pip install opencv-python
```

### Configuring PYTHONPATH environment variable
A PYTHONPATH variable must be created that points to the \models, \models\research, and \models\research\slim directories. Do this by issuing the following commands (from any directory):
```
(tensorflow1) C:\> set PYTHONPATH=C:\tensorflow1\models;C:\tensorflow1\models\research;C:\tensorflow1\models\research\slim
```
### Compiling Protobufs and run setup.py

In the Command Prompt, change directories to the \models\research directory:
```
(tensorflow1) C:\> cd C:\tensorflow1\models\research
```

Then we run the following command into the Command Prompt:
```
protoc --python_out=. .\object_detection\protos\anchor_generator.proto .\object_detection\protos\argmax_matcher.proto .\object_detection\protos\bipartite_matcher.proto .\object_detection\protos\box_coder.proto .\object_detection\protos\box_predictor.proto .\object_detection\protos\eval.proto .\object_detection\protos\faster_rcnn.proto .\object_detection\protos\faster_rcnn_box_coder.proto .\object_detection\protos\grid_anchor_generator.proto .\object_detection\protos\hyperparams.proto .\object_detection\protos\image_resizer.proto .\object_detection\protos\input_reader.proto .\object_detection\protos\losses.proto .\object_detection\protos\matcher.proto .\object_detection\protos\mean_stddev_box_coder.proto .\object_detection\protos\model.proto .\object_detection\protos\optimizer.proto .\object_detection\protos\pipeline.proto .\object_detection\protos\post_processing.proto .\object_detection\protos\preprocessor.proto .\object_detection\protos\region_similarity_calculator.proto .\object_detection\protos\square_box_coder.proto .\object_detection\protos\ssd.proto .\object_detection\protos\ssd_anchor_generator.proto .\object_detection\protos\string_int_label_map.proto .\object_detection\protos\train.proto .\object_detection\protos\keypoint_box_coder.proto .\object_detection\protos\multiscale_anchor_generator.proto .\object_detection\protos\graph_rewriter.proto .\object_detection\protos\calibration.proto .\object_detection\protos\flexible_grid_anchor_generator.proto
```
This creates a name_pb2.py file from every name.proto file in the \object_detection\protos folder.

Finally, run the following commands from the C:\tensorflow1\models\research directory:
```
(tensorflow1) C:\tensorflow1\models\research> python setup.py build
(tensorflow1) C:\tensorflow1\models\research> python setup.py install
```
### Gather and Label Pictures
We created 9 classes for our classification of products and collected 100 pictures for training each class.
The Images are placed in C:\tensorflow1\models\research\object_detection\images here in github its in <b>images</b> directory.
In images we split the images into test and train sub-directories.

Then using the Labelimg software we lable each image and this inturn generates xml file for each image

### Generate Training Data
We run the following command in the Prompt
```
(tensorflow1) C:\tensorflow1\models\research\object_detection> python xml_to_csv.py
```
This generates test_labels.csv and train_labels.csv files by taking all the xmls in the test and train sub-directories in images folder.

Then we create label map file named as labelmap.pbtxt in training directory, this is the file which have our previously mentioned 9 class names


Then, we generated the TFRecord files by issuing these commands from the \object_detection folder:
```
python generate_tfrecord.py --csv_input=images\train_labels.csv --image_dir=images\train --output_path=train.record
python generate_tfrecord.py --csv_input=images\test_labels.csv --image_dir=images\test --output_path=test.record
```
These generates a train.record and a test.record file in \object_detection, this is used to train the model.

Then we configured and fine tuned the steprate,epoch values and the locations for the data to be trained in the config file.
### Training
```
python train.py --logtostderr --train_dir=training/ --pipeline_config_path=training/faster_rcnn_inception_v2_pets.config
```
By Running this Command we generate the checkpoint files which stores the trained data model weights after 2-3 hrs of training.

<p align="center">
  <img src="https://imgur.com/download/2ZMhsob">
</p>

### Then we Export Inference Graph
```
python export_inference_graph.py --input_type image_tensor --pipeline_config_path training/faster_rcnn_inception_v2_pets.config --trained_checkpoint_prefix training/model.ckpt-20012 --output_directory inference_graph
```
This generates our final .pb file (which is our final Inference model) in inference_graph directory 

# Code Explanation

### Web application

<p align="center">
  <img src="https://imgur.com/download/pxzl9Tx">
</p>

After completion of the training of the model we need to create an interface to take image of products.
By using the webcamera take the picture of products and save them in the folder "upload/".
Then php server that is apache executes the following shell command as specified in the index.php
```
$p="activate tensorflow1 & cd C:\\tensorflow1\\models\\research\\object_detection & python Object_detection_image.py C:\\xampp\\htdocs\\webapp\\upload\\"."ImageName.extension";
$out=shell_exec($p);
 ```
The result(List of product names,List of Products areas) will be stored in the $out varaible.
This tuple is passed by the object_detection_image.py file.
Then the webaplication php processes the tuple passed and generates the bill.

