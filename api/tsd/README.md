# Autostrad Rent a Car - TSD XML API Documentation

## Overview

This TSD (Travel Software Developers) XML API provides OTA (OpenTravel Alliance) compliant XML endpoints for car rental integration with Auto Europe and other travel platforms.

## Base URL

```
Production: https://api.autostrad.com/hub/api/tsd/
```

## Authentication

All requests must include credentials in the `POS` (Point of Sale) element:

```xml
<POS>
    <Source>
        <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
    </Source>
</POS>
```

## Supported Operations

### 1. OTA_VehLocSearchRQ - Get Locations

Retrieves all available rental locations.

**Request:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehLocSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <POS>
        <Source>
            <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
        </Source>
    </POS>
</OTA_VehLocSearchRQ>
```

**Response:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehLocSearchRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <Success/>
    <VehMatchedLocs>
        <VehMatchedLoc>
            <LocationDetail AtAirport="true" Code="1" Name="Dubai International Airport - Terminal 1">
                <Address>
                    <CityName>Dubai</CityName>
                    <CountryName Code="AE">United Arab Emirates</CountryName>
                </Address>
                <Telephone PhoneNumber="+971-4-XXXXXXX"/>
                <Position Latitude="25.2532" Longitude="55.3657"/>
            </LocationDetail>
        </VehMatchedLoc>
    </VehMatchedLocs>
</OTA_VehLocSearchRS>
```

---

### 2. OTA_VehAvailRateRQ - Get Vehicle Availability & Rates

Search for available vehicles and their rates for a specific rental period.

**Request:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehAvailRateRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <POS>
        <Source>
            <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
        </Source>
    </POS>
    <VehAvailRQCore>
        <VehRentalCore PickUpDateTime="2026-02-01T08:00:00" ReturnDateTime="2026-02-05T08:00:00">
            <PickUpLocation LocationCode="1"/>
            <ReturnLocation LocationCode="1"/>
        </VehRentalCore>
    </VehAvailRQCore>
</OTA_VehAvailRateRQ>
```

**Response:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehAvailRateRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <Success/>
    <VehAvailRSCore>
        <VehRentalCore PickUpDateTime="2026-02-01T08:00:00" ReturnDateTime="2026-02-05T08:00:00">
            <PickUpLocation LocationCode="1">Dubai International Airport</PickUpLocation>
            <ReturnLocation LocationCode="1">Dubai International Airport</ReturnLocation>
        </VehRentalCore>
        <VehVendorAvails>
            <VehVendorAvail>
                <Vendor CompanyShortName="Autostrad" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>
                <VehAvails>
                    <VehAvail>
                        <VehAvailCore Status="Available">
                            <Vehicle AirConditionInd="true" TransmissionType="Automatic" 
                                     PassengerQuantity="5" BaggageQuantity="2" Code="2">
                                <VehMakeModel Name="Toyota Yaris or Similar" Code="EDMR"/>
                            </Vehicle>
                            <RentalRate>
                                <VehicleCharges>
                                    <VehicleCharge Amount="280.00" CurrencyCode="AED" Purpose="Base Rate">
                                        <TaxAmounts>
                                            <TaxAmount Total="14.00" Percentage="5" Description="VAT"/>
                                        </TaxAmounts>
                                    </VehicleCharge>
                                </VehicleCharges>
                            </RentalRate>
                            <TotalCharge RateTotalAmount="294.00" CurrencyCode="AED"/>
                            <PricedEquips>
                                <PricedEquip>
                                    <Equipment EquipType="SCDW" Description="Zero Excess Protection"/>
                                    <Charge Amount="57.75" CurrencyCode="AED"/>
                                </PricedEquip>
                            </PricedEquips>
                        </VehAvailCore>
                    </VehAvail>
                </VehAvails>
            </VehVendorAvail>
        </VehVendorAvails>
    </VehAvailRSCore>
</OTA_VehAvailRateRS>
```

---

### 3. OTA_VehResRQ - Create New Reservation

Create a new vehicle reservation.

**Request:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehResRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <POS>
        <Source>
            <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
        </Source>
    </POS>
    <VehResRQCore>
        <VehRentalCore PickUpDateTime="2026-02-01T08:00:00" ReturnDateTime="2026-02-05T08:00:00">
            <PickUpLocation LocationCode="1"/>
            <ReturnLocation LocationCode="1"/>
        </VehRentalCore>
        <Customer>
            <Primary>
                <PersonName>
                    <GivenName>John</GivenName>
                    <Surname>Doe</Surname>
                </PersonName>
                <Telephone PhoneNumber="+971501234567"/>
                <Email>john.doe@example.com</Email>
            </Primary>
        </Customer>
        <VehPref Code="2"/>
        <TotalCharge RateTotalAmount="294.00"/>
        <RateQualifier RateQualifier="AE-123456789"/>
    </VehResRQCore>
    <VehResRQInfo>
        <ArrivalDetails TransportationCode="EK123"/>
        <SpecialEquipPrefs>
            <SpecialEquipPref EquipType="SCDW" Quantity="1"/>
            <SpecialEquipPref EquipType="GPS" Quantity="1"/>
        </SpecialEquipPrefs>
    </VehResRQInfo>
</OTA_VehResRQ>
```

**Response:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehResRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <Success/>
    <VehResRSCore>
        <VehReservation ReservationStatus="Reserved">
            <VehSegmentCore>
                <ConfID Type="Supplier" ID="103501"/>
                <Vendor CompanyShortName="Autostrad" Code="AUTOSTRAD">Autostrad Rent a Car</Vendor>
                <!-- Full reservation details -->
            </VehSegmentCore>
        </VehReservation>
    </VehResRSCore>
</OTA_VehResRS>
```

---

### 4. OTA_VehCancelRQ - Cancel Reservation

Cancel an existing reservation.

**Request:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehCancelRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <POS>
        <Source>
            <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
        </Source>
    </POS>
    <VehCancelRQCore>
        <UniqueID Type="Supplier" ID="103501"/>
    </VehCancelRQCore>
</OTA_VehCancelRQ>
```

**Response:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehCancelRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <Success/>
    <VehCancelRSCore CancelStatus="Cancelled">
        <VehReservation ReservationStatus="Cancelled">
            <!-- Cancelled reservation details -->
        </VehReservation>
    </VehCancelRSCore>
</OTA_VehCancelRS>
```

---

### 5. OTA_VehModifyRQ - Modify Reservation

Modify contact details of an existing reservation.

**Note:** Only the following fields can be modified:
- Customer Name
- Customer Phone
- Customer Email
- Flight Number

Date/location/vehicle changes require cancellation and re-booking.

**Request:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehModifyRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <POS>
        <Source>
            <RequestorID ID="YOUR_USERNAME" MessagePassword="YOUR_PASSWORD"/>
        </Source>
    </POS>
    <VehModifyRQCore>
        <UniqueID Type="Supplier" ID="103501"/>
        <Customer>
            <Primary>
                <PersonName>
                    <GivenName>Jane</GivenName>
                    <Surname>Doe</Surname>
                </PersonName>
                <Telephone PhoneNumber="+971509876543"/>
            </Primary>
        </Customer>
        <ArrivalDetails TransportationCode="EK456"/>
    </VehModifyRQCore>
</OTA_VehModifyRQ>
```

---

## Equipment/Extra Types

| Code | Description |
|------|-------------|
| CDW | Collision Damage Waiver (40-50% excess reduction) |
| SCDW | Super CDW - Zero Excess / Full Protection |
| PAI | Personal Accident Insurance |
| GPS | GPS Navigation System |
| CST | Child Safety Seat |
| ADR | Additional Driver |

## Error Handling

All errors return an `OTA_VehErrorRS` response:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<OTA_VehErrorRS xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.0">
    <Errors>
        <Error Type="Error" Code="ERR_AUTH">Invalid credentials or broker not found</Error>
    </Errors>
</OTA_VehErrorRS>
```

### Error Codes

| Code | Description |
|------|-------------|
| ERR_PARSE | Invalid or missing XML request |
| ERR_AUTH | Invalid credentials |
| ERR_PARAMS | Missing required parameters |
| ERR_LEADTIME | Pickup time doesn't meet lead time requirements |
| ERR_NOTFOUND | Reservation not found |
| ERR_ALREADY_CANCELLED | Reservation already cancelled |
| ERR_UNSUPPORTED | Unsupported operation |

## Important Notes

1. **Lead Time**: Each location has a minimum lead time (typically 3-5 hours before pickup)
2. **Currency**: All amounts are in AED (UAE Dirhams)
3. **VAT**: 5% VAT is included in all rates
4. **SCDW Calculation**: Zero Excess Rate = `totalValue` + `scdw`
5. **Customer Names**: Only English characters are accepted
6. **Extensions**: Rate extensions must be done at the counter

## Contact

For technical support:
- Email: osamah.kenawy@mwasalat.ae
- Phone: +971 2 815 2706

For reservations:
- Email: reservations@autostrad.com
- Phone: +971-4-3846012

