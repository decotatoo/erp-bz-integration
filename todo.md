# General

<!-- @TODAY -->
## Processing SO Online Flow

1. [x] Payment confirmed, set the order STATUS to "processing", and send the data to erp 
1. [x] consider the "Pending Release Stock counter" for the stock syncornation.
1. STAFF take action [release the stock and deduct the "Pending Release Stock counter"]: scan the product label (barcode) in stock.
1. once the order stock fulfilled, allow ADMIN to take action to set the order's released value to "true"
1. allow to print Invoice and Delivery Order
1. if the order's released value to true, 
1. allow to input the AWB number and provider
1. once the AWB number and provider submitted, set the order's STATUS to "completed"
1. show on the WooCommerce site the order status as "completed" and another another status named "delivered" with the hyperlink to courier's tracker page.

## Reporting

1. [] Report
1. [] Invoice and Delivery Order

## Queue / Scheduled

1. [x] consider the "Pending Release Stock counter" for the stock syncornation.
1. check for order with delivery STATUS "shipped", if the item delivered, set the delivery STATUS to "delivered"

## Shipment

### Simulate Bin Packing:

1. [x] Masterbox/Bin management page
1. [] Product's Dimension management page
1. Product's gross acording to the Product's Dimension

1. [x] on the cart page, send cart data to erp
1. [x] simulate the bin packing and return the simulation result to cart page. the simulation also saved to erp database for future referencing
1. [x] if cart page updated, repeat the step 1
1. [x] in checkout process, include the simulation result that to be sent via webhook to erp
1. reference the order number to bin packing simulation record

### Local courier

1. [x] adjust the current "woongkir" plugin to integrated well with rajaongkir and bin packing simulation
1. integrate with paxel pricing 

### International courier

1. [] integrate with DHL pricing 

### Gojek

TBA

## Payment

### currency

- [x] available currency IDR & HKD with fixed rate. (manual setup on wp-admin page)
- [x] toggle the payment provider based on currency? (manual setup on wp-admin page)
- [x] currency switcher on Products Archive page
-  shipment cost based on IDR, and converted to HKD if needed (DHL)

## WooCommmerce

- [x] Tax. Berdasarkan Alamat pengiriman (indonesia). Ppn 10%. Ditampilkan disaat checkout saja.
- [x] Product detail
- [] order detail page / Invoice
- minor style on completed payment order
- add shipment tracking link on order list and order detail page
- let guest to search order by reference
- [x] notify on product re-stock
- [x] detect the region and offer the language if visited from Indonesia
- Permission and Role for administrating the site. E.g.,
    - update product galery
    - SEO thing

## Documenting the ERP integration

- [] Installation
- erd
- flowchart
- usecase
- 

## Documenting the WooCommerce site

- [] Installation
- workflow



