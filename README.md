+-------------------+       +-------------------+
|     Customer      |       |      Order        |
+-------------------+       +-------------------+
| - CustomerID (PK) | 1 — * | - OrderID (PK)    |
| - Name            |       | - CustomerID (FK) |
| - Email           |       | - OrderDate       |
| - Phone           |       | - TotalAmount     |
| - Address         |       | - Status          |
+-------------------+       +-------------------+
                                 |
                                 | 1 — *
                                 v
+-------------------+       +-------------------+
|     Product       |       |    OrderItem      |
+-------------------+       +-------------------+
| - ProductID (PK)  | 1 — * | - OrderItemID (PK)|
| - Name            |       | - OrderID (FK)    |
| - Description     |       | - ProductID (FK)  |
| - Price           |       | - Quantity        |
| - StockQuantity   |       | - UnitPrice       |
+-------------------+       +-------------------+

+-------------------+
|    Category       |
+-------------------+
| - CategoryID (PK) |
| - Name            |
| - Description     |
+-------------------+
          |
          | 1 — *
          v
+-------------------+
|     Product       |
+-------------------+
| (See above)       |
+-------------------+
