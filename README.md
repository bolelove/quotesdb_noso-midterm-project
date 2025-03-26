# Noah Solomon
# Mid-Term Project INF 653
# Project Name: https://quotesdb-noso-midterm-project.onrender.com/
# Dave Gray 

 > Build a PHP OOP REST API for quotations- both famous quotes and user submissions
 > ALL quotes have ALL 3 of the following:
   > Quote (the quotation itself)
   > Author
   > Category
 > Created a database named “quotesdb” with 3 tables and these specific column names:
  > quotes (id, quote, author_id, category_id)- the last two are foreign keys
  > authors (id, author)
  > categories (id, category)
  > id is the primary key in each table
  > The id column should also auto-increment
  > All columns should be non-null
  > Support for this creation process will be documented in Blackboard
> Response requirements:
  > All requests should provide a JSON data response.
  > All requests for quotes should return the id, quote, author (name), and category (name)
  > All requests for authors should return the id and author fields.
  > All requests for categories should return the id and category fields.
  > Appropriate not found and missing parameter messages as indicated.

> Create Database utilizing Postgres with...
 > Minimum 5 categories
 > Minimum 5 authors
 > Minimum 25 quotes total for initial data

> Built Docker container to deploy PHP with Apache on Render.com

