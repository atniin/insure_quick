# insure_quick
 A booking system (homework).

# Usage steps
1. Clone the repo
2. Create .env file (use .env.example as an example and fill in the blanks)
3. Run docker-compose up or docker-compose up -d in the project's directory
4. Import postman-collection.json to Postman and start testing

# Overview
GET Endpoints:
    GET /api/agent/:id - Retrieve details of an agent by their ID.
    GET /api/client/:id - Retrieve details of a client by their ID.
    GET /api/booking/freeTimes/:date - Get available booking timeslots for a specific date.

POST Endpoints:
    POST /api/agent/ - Create a new agent with the provided personal_code and name.
    POST /api/client/ - Create a new client with the provided personal_code and name.
    POST /api/booking/ - Create a new booking with the specified client_id, start_datetime, and end_datetime.

# Ideas for the future
This homework focuses on the basics of the booking system. Below are steps and ideas for extending and improving the application:
1. Database Considerations:
    Consider using to MongoDB for GET routes and/or introducing a caching mechanism for handling the available timeslots efficiently, especially as the application scales.

2. New Endpoints:
The following endpoints should be considered for future development:
    Get Booking: Endpoint to retrieve information about a specific booking.
    Edit Booking: Endpoint to update details of an existing booking.
    Delete Booking: Endpoint to delete a booking.
    Edit Agent: Endpoint to update details of an agent.
    Delete Agent: Endpoint to remove an agent from the system.
    Agent Availability: Consider adding a table for agent_availability to manage agent availability, including sick days, vacations, etc.

3. Future considerations:
    A client should likely be limited to making only one booking at a time. Additionally, it might be necessary to implement a timezone layer (currently using UTC+0), along with considerations for authentication and various UI/UX nuances.

5. Scalability:
The system is built using Domain-Driven Design (DDD), which makes it easy to develop further and scale the application as needed. Future changes can be implemented in a modular manner by following the current DDD structure.

Routes are defined in routes/api.php.

