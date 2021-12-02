# General

## Documenting the ERP

- 
- 
-

## Documenting the WooCommerce site

- workflow
- 

## Processing SO Online

1. Confirming the payment, and set the STATUS to "processing"
1. staff action: scan the product label (barcode) in stock
1. once the order stock fulfilled, allow action to set the order's released value to "true"
1. if the order's released value to true, then allow to input the AWB number and provider
1. once the AWB number and provider submitted, set the order's STATUS to "completed"
1. show on the WooCommerce site the order status as "completed" and another another status named "delivered" with the hyperlink to courier's tracker page.

## Routine / Scheduled

1. check for order with delivery STATUS "shipped", if the item delivered, set the delivery STATUS to "delivered"

## Shipment

### Simulate Bin Packing:

1. on the cart page, send cart data to erp
1. simulate the bin packing and return the simulation result to cart page. the simulation also saved to erp database for future referencing
1. if cart page updated, repeat the step 1
1. in checkout process, include the simulation result that to be sent via webhook to erp
1. reference the order number to bin packing simulation record

### Local courier

1. adjust the current tool to integrated well with rajaongkir

### Oversea courier

1. integrate with DHL pricing 

### Gojek

tba

## Payment

### currency

- [x] available currency IDR & HKD with fixed rate. (manual setup on wp-admin page)
- [x] toggle the payment provider based on currency? (manual setup on wp-admin page)
- shipment cost based on IDR, and converted to HKD if needed

# WooCommmerce

- overall order detail page
- minor style on completed payment order
- add shipment tracking link on order list and order detail page
- let guest to search order by reference
- 





