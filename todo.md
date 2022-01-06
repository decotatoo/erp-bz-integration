# General

## Catalog & Category

- [x] Catalog management (Hard-coded 2 season, Autumn-Winter + Spring-Summer)
- [x] Category management

<!-- @TODAY -->
## Processing SO Online Flow

- [x] consider the "Pending Release Stock counter" for the stock syncornation.

- [x] Payment confirmed, order STATUS = "processing". Send data to erp
- [ ] _STAFF_ take action [release the stock and deduct the "Pending Release Stock counter"]: scan the product label (barcode) in stock.
- [ ] once the order stock fulfilled, automatic change the release status to "OK" and order STATUS to "completed" (send data to woocommerce), and allow to print **Invoice and Delivery Order**
- [ ] (optional action) allow to input the no resi / awb number and shipment provider

- [ ] show on the WooCommerce site the order status as "completed" and another another status named "delivered" with the hyperlink to courier's tracker page.

<!-- @TODAY -->
## Reporting

- [ ] Report

## Queue / Scheduled

- [x] consider the "Pending Release Stock counter" for the stock syncornation.
- [ ] check for order with delivery STATUS "shipped", if the item delivered, set the delivery STATUS to "delivered"

## Shipment

### Simulate Bin Packing:

```
e.g:
UnitBox: P × l × t = 125 × 30 × 40
MasterBox Outer: P × l × t = 215 × 155 × 85
MasterBox Inner: P × l × t = 210 × 150 × 80
Max Weight: 480
```

- [x] Masterbox/Bin management page
- [x] UnitBox management page

- [x] on the cart page, send cart data to erp
- [x] simulate the bin packing and return the simulation result to cart page. the simulation also saved to erp database for future referencing
- [x] if cart page updated, repeat the step 1
- [x] in checkout process, include the simulation result that to be sent via webhook to erp
- [x] reference the order number to bin packing simulation record

- [x] rounding on the subtotal instead per bin (WooCommerce Side)


### courier

- [x] adjust the current "woongkir" plugin to integrated well with rajaongkir and bin packing simulation
- integrate with paxel pricing 
- [ ] integrate with DHL pricing 
- [ ] integrate with Gojek (tba) 

## Payment

### currency

- [x] available currency IDR & HKD with fixed rate. (manual setup on wp-admin page)
- [x] toggle the payment provider based on currency? (manual setup on wp-admin page)
- [x] currency switcher on Products Archive page
- [x] shipment cost based on IDR, and converted to HKD if needed (current rate powered by currencylayer)

## WooCommmerce

- [ ] Term and Condition confirmation
- [ ] Paypal
- [x] Tax. Berdasarkan Alamat pengiriman (indonesia). Ppn 10% (item saja). Ditampilkan disaat checkout saja.
- [x] Product detail
- [ ] order detail page / Invoice
- [ ] minor style on completed payment order
- [ ] add shipment tracking link on order list and order detail page
- [ ] let guest to search order by reference
- [x] notify on product re-stock
- [x] detect the region and offer the language if visited from Indonesia
- Permission and Role for administrating the site. E.g.,
    - update product galery
    - SEO thing

- Product Introduction (custom page)
- Field to update page and design the page


## Documenting the ERP integration

- [ ] Installation
- ERD
- flowchart
- usecase

## Documenting the WooCommerce site

- [ ] Installation
- workflow

# Latest

- Migrate old customer and order record

# Dashboard

- Production Dashboard:
    - calendar
    - product production

- Calendar with internal agenda / event data.
    allow to share the agenda to internal staff.

