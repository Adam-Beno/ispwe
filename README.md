# ISPWE
Semestral assignment

## What is this about
Basically the main goal is to create a very simple online hosting portal, which is supposed to demonstrate our capability to setup and maintain an Apache 2.* server with indefinite number of virtual hosts.

## Technologies used
Well since we were limited to Apache platform we kinda had to stick with PHP 7 for the user interface and folder creation part.

## How did we proceed
We initially tried to use pre-build platform called [https://www.ispconfig.org/](https://www.ispconfig.org/), but that failed since we were not able
to set this up on the school server and we had to fall back to our very own solution.

## How it works
From the technical point of view, every subdomain has it's own virtual host, this is done via vhosts module which then allows us to setup each subdomain as a standalone server.
