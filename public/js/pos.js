/**
 * POS (Point of Sale) Booking System JavaScript
 * Handles movie, showtime, and seat selection for cinema bookings
 */

document.addEventListener('DOMContentLoaded', function() {
    // State variables
    let state = {
        selectedMovie: null,
        selectedShowtime: null,
        selectedSeats: [],
        selectedFoodItems: [], // Array of {id, name, price, quantity}
        selectedDrinks: [],   // Array of {id, name, price, quantity}
        orderSummary: {
            tickets: 0,
            foodItems: 0,
            drinks: 0,
            total: 0
        }
    };

    // Initialize page
    function initializePage() {
        // Show the first step only
        showStep(1);
        
        // Set up event listeners for movie cards
        document.querySelectorAll('.movie-card').forEach(card => {
            card.addEventListener('click', function() {
                const movieId = this.dataset.movieId;
                const movieTitle = this.dataset.movieTitle;
                const movieDuration = this.dataset.movieDuration;
                const movieGenre = this.dataset.movieGenre;
                
                selectMovie(movieId, movieTitle, movieDuration, movieGenre);
            });
        });

        // Back step buttons
        document.querySelectorAll('.back-step').forEach(btn => {
            btn.addEventListener('click', function() {
                const prevStep = parseInt(this.dataset.step);
                showStep(prevStep);
            });
        });
        
        // Continue to food button
        const continueToFoodBtn = document.getElementById('continue-to-food-btn');
        if (continueToFoodBtn) {
            continueToFoodBtn.addEventListener('click', function() {
                showStep(4);
            });
        }
        
        // Continue to checkout button
        const continueToCheckoutBtn = document.getElementById('continue-to-checkout-btn');
        if (continueToCheckoutBtn) {
            continueToCheckoutBtn.addEventListener('click', function() {
                showStep(5);
            });
        }
        
        // Skip food button
        const skipFoodBtn = document.getElementById('skip-food-btn');
        if (skipFoodBtn) {
            skipFoodBtn.addEventListener('click', function() {
                showStep(5);
            });
        }
        
        // Reset seats button
        const resetSeatsBtn = document.getElementById('reset-seats-btn');
        if (resetSeatsBtn) {
            resetSeatsBtn.addEventListener('click', function() {
                state.selectedSeats = [];
                document.querySelectorAll('.seat.selected').forEach(seat => {
                    seat.classList.remove('selected');
                });
                updateSelectedSeats();
                updateOrderSummary();
            });
        }
        
        // Finalize booking button
        const finalizeBookingBtn = document.getElementById('finalize-booking-btn');
        if (finalizeBookingBtn) {
            finalizeBookingBtn.addEventListener('click', function() {
                finalizeBooking();
            });
        }
        
        // Reset order summary
        resetOrderSummary();
        
        // New booking button
        const newBookingBtn = document.getElementById('new-booking-btn');
        if (newBookingBtn) {
            newBookingBtn.addEventListener('click', function() {
                resetOrderSummary();
                showStep(1);
            });
        }
        
        // Food & drink quantity buttons
        setupFoodDrinkControls();
    }

    // Set up food and drink quantity controls
    function setupFoodDrinkControls() {
        // Increase quantity buttons
        document.querySelectorAll('.increase-qty').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                const itemType = this.dataset.itemType;
                const qtyInput = document.querySelector(`.qty-input[data-item-id="${itemId}"][data-item-type="${itemType}"]`);
                
                if (qtyInput) {
                    const itemName = qtyInput.dataset.itemName;
                    const itemPrice = parseFloat(qtyInput.dataset.itemPrice);
                    
                    // Increment quantity
                    const newQty = parseInt(qtyInput.value) + 1;
                    qtyInput.value = newQty;
                    
                    addItem(itemType, itemId, itemName, itemPrice);
                }
            });
        });
        
        // Decrease quantity buttons
        document.querySelectorAll('.decrease-qty').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                const itemType = this.dataset.itemType;
                const qtyInput = document.querySelector(`.qty-input[data-item-id="${itemId}"][data-item-type="${itemType}"]`);
                
                if (qtyInput && parseInt(qtyInput.value) > 0) {
                    // Decrement quantity
                    const newQty = parseInt(qtyInput.value) - 1;
                    qtyInput.value = newQty;
                    
                    removeItem(itemType, itemId);
                }
            });
        });
        
        // Add item buttons
        document.querySelectorAll('.add-item-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                const itemType = this.dataset.itemType;
                const itemName = this.dataset.itemName;
                const itemPrice = parseFloat(this.dataset.itemPrice);
                
                // Find quantity input
                const qtyInput = document.querySelector(`.qty-input[data-item-id="${itemId}"][data-item-type="${itemType}"]`);
                
                // Increment quantity
                const newQty = parseInt(qtyInput.value) + 1;
                qtyInput.value = newQty;
                
                addItem(itemType, itemId, itemName, itemPrice);
            });
        });
    }

    // Show a specific step and hide others
    function showStep(stepNumber) {
        document.querySelectorAll('.booking-step').forEach(step => {
            step.classList.add('d-none');
        });
        
        const currentStep = document.getElementById(`booking-step-${stepNumber}`);
        if (currentStep) {
            currentStep.classList.remove('d-none');
            
            // Special handling for certain steps
            if (stepNumber === 2 && state.selectedMovie) {
                fetchShowtimes(state.selectedMovie.id);
            } else if (stepNumber === 3 && state.selectedShowtime) {
                fetchSeats(state.selectedShowtime.hall_id);
            }
        }
    }

    // Select a movie and fetch its showtimes
    function selectMovie(movieId) {
        // Highlight selected movie
        document.querySelectorAll('.movie-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const selectedCard = document.querySelector(`.movie-card[data-movie-id="${movieId}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
            
            // Extract movie data from the selected card
            const movieTitle = selectedCard.querySelector('.fw-bold').textContent;
            const movieInfo = selectedCard.querySelector('.small.text-muted').textContent;
            const movieDuration = movieInfo.split('|')[0].trim().replace(' min', '');
            const movieGenre = movieInfo.split('|')[1].trim();
            
            // Update state
            state.selectedMovie = {
                id: movieId,
                title: movieTitle,
                duration: movieDuration,
                genre: movieGenre
            };
            state.selectedShowtime = null;
            state.selectedSeats = [];
            
            // Update order summary
            updateOrderSummary();
            
            // Go to next step
            showStep(2);
        }
    }

    // Mock data for testing
    const MOCK_DATA = {
        showtimes: {
            1: [ // Movie 1 showtimes
                {
                    id: 101,
                    hall_id: 1,
                    hall: { name: 'Hall A', hall_type: 'Standard' },
                    showtime: '2023-09-20T14:30:00',
                    ticket_price: 12.50,
                    is_active: true
                },
                {
                    id: 102,
                    hall_id: 2,
                    hall: { name: 'Hall B', hall_type: 'Premium' },
                    showtime: '2023-09-20T18:00:00',
                    ticket_price: 15.00,
                    is_active: true
                },
                {
                    id: 103,
                    hall_id: 1,
                    hall: { name: 'Hall A', hall_type: 'Standard' },
                    showtime: '2023-09-21T13:00:00',
                    ticket_price: 10.00,
                    is_active: true
                }
            ],
            2: [ // Movie 2 showtimes
                {
                    id: 201,
                    hall_id: 3,
                    hall: { name: 'Hall C', hall_type: 'VIP' },
                    showtime: '2023-09-20T15:00:00',
                    ticket_price: 18.00,
                    is_active: true
                },
                {
                    id: 202,
                    hall_id: 1,
                    hall: { name: 'Hall A', hall_type: 'Standard' },
                    showtime: '2023-09-21T16:30:00',
                    ticket_price: 12.00,
                    is_active: true
                }
            ],
            3: [ // Movie 3 showtimes
                {
                    id: 301,
                    hall_id: 2,
                    hall: { name: 'Hall B', hall_type: 'Premium' },
                    showtime: '2023-09-20T10:00:00',
                    ticket_price: 12.00,
                    is_active: true
                },
                {
                    id: 302,
                    hall_id: 1,
                    hall: { name: 'Hall A', hall_type: 'Standard' },
                    showtime: '2023-09-20T19:30:00',
                    ticket_price: 14.00,
                    is_active: true
                }
            ]
        },
        seats: {
            1: [ // Hall 1 seats
                { id: 11, row: 'A', number: 1, type: 'regular', is_available: true, is_booked: false },
                { id: 12, row: 'A', number: 2, type: 'regular', is_available: true, is_booked: false },
                { id: 13, row: 'A', number: 3, type: 'regular', is_available: true, is_booked: true },
                { id: 14, row: 'A', number: 4, type: 'regular', is_available: true, is_booked: false },
                { id: 15, row: 'A', number: 5, type: 'regular', is_available: true, is_booked: false },
                { id: 16, row: 'B', number: 1, type: 'premium', is_available: true, is_booked: false },
                { id: 17, row: 'B', number: 2, type: 'premium', is_available: true, is_booked: false },
                { id: 18, row: 'B', number: 3, type: 'premium', is_available: true, is_booked: false },
                { id: 19, row: 'B', number: 4, type: 'premium', is_available: true, is_booked: true },
                { id: 20, row: 'B', number: 5, type: 'premium', is_available: true, is_booked: false },
                { id: 21, row: 'C', number: 1, type: 'vip', is_available: true, is_booked: false },
                { id: 22, row: 'C', number: 2, type: 'vip', is_available: true, is_booked: false },
                { id: 23, row: 'C', number: 3, type: 'vip', is_available: true, is_booked: false },
                { id: 24, row: 'C', number: 4, type: 'vip', is_available: true, is_booked: false },
                { id: 25, row: 'C', number: 5, type: 'vip', is_available: true, is_booked: false }
            ],
            2: [ // Hall 2 seats
                { id: 26, row: 'A', number: 1, type: 'regular', is_available: true, is_booked: false },
                { id: 27, row: 'A', number: 2, type: 'regular', is_available: true, is_booked: true },
                { id: 28, row: 'A', number: 3, type: 'regular', is_available: true, is_booked: false },
                { id: 29, row: 'A', number: 4, type: 'regular', is_available: true, is_booked: false },
            ]
        }
    };

    // Fetch showtimes for selected movie
    function fetchShowtimes(movieId) {
        const isTestEnvironment = window.location.pathname.includes('pos-test.html');
        
        if (isTestEnvironment) {
            console.log('Using mock data for showtimes');
            const showtimeContainer = document.getElementById('showtimes-container');
            const loadingIndicator = document.getElementById('showtimes-loading');
            
            if (!showtimeContainer || !loadingIndicator) return;
            
            // Show loading indicator
            showtimeContainer.innerHTML = '';
            loadingIndicator.classList.remove('d-none');
            
            // Simulate network delay
            setTimeout(() => {
                loadingIndicator.classList.add('d-none');
                
                const data = MOCK_DATA.showtimes[movieId] || [];
                
                if (data.length === 0) {
                    showtimeContainer.innerHTML = '<div class="alert alert-info">No showtimes available for this movie.</div>';
                    return;
                }
                
                // Group showtimes by date
                const showtimesByDate = {};
                data.forEach(showtime => {
                    const date = new Date(showtime.showtime);
                    const dateStr = date.toLocaleDateString();
                    if (!showtimesByDate[dateStr]) {
                        showtimesByDate[dateStr] = [];
                    }
                    showtimesByDate[dateStr].push({
                        id: showtime.id,
                        hall_id: showtime.hall_id,
                        hall_name: showtime.hall ? showtime.hall.name : `Hall #${showtime.hall_id}`,
                        time: date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                        price: showtime.ticket_price,
                        full_date: date
                    });
                });
                
                // Create date tabs
                let html = '<div class="showtime-dates mb-3"><ul class="nav nav-pills">';
                let first = true;
                Object.keys(showtimesByDate).forEach((date, index) => {
                    html += `<li class="nav-item">
                        <a class="nav-link ${first ? 'active' : ''}" 
                           href="#date-tab-${index}" 
                           data-bs-toggle="tab">${date}</a>
                    </li>`;
                    first = false;
                });
                html += '</ul></div>';
                
                // Create time slots for each date
                html += '<div class="tab-content">';
                first = true;
                Object.entries(showtimesByDate).forEach(([date, showtimes], index) => {
                    html += `<div class="tab-pane fade ${first ? 'show active' : ''}" id="date-tab-${index}">
                        <div class="row">`;
                    
                    showtimes.forEach(showtime => {
                        html += `<div class="col-md-3 mb-3">
                            <div class="card showtime-card" data-showtime-id="${showtime.id}" 
                                 data-hall-id="${showtime.hall_id}" data-price="${showtime.price}">
                                <div class="card-body">
                                    <h5 class="card-title">${showtime.time}</h5>
                                    <p class="card-text">${showtime.hall_name}</p>
                                </div>
                            </div>
                        </div>`;
                    });
                    
                    html += '</div></div>';
                    first = false;
                });
                html += '</div>';
                
                showtimeContainer.innerHTML = html;
                
                // Add event listeners to showtime cards
                document.querySelectorAll('.showtime-card').forEach(card => {
                    card.addEventListener('click', function() {
                        selectShowtime(
                            this.dataset.showtimeId,
                            this.dataset.hallId,
                            parseFloat(this.dataset.price)
                        );
                    });
                });
            }, 1000);
            
            return;
        }
        
        // If not in test environment, use the original implementation
        const showtimeList = document.getElementById('showtime-list');
        const showtimeContainer = document.getElementById('showtime-container');
        const loadingIndicator = showtimeContainer.querySelector('.loading-indicator');
        
        if (!showtimeList || !loadingIndicator) return;
        
        // Show loading indicator
        showtimeList.classList.add('d-none');
        loadingIndicator.classList.remove('d-none');
        
        // Fetch showtimes from server
        fetch(`/dashboard/pos/get-movie-halls/${movieId}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('d-none');
                
                if (data.length === 0) {
                    showtimeList.innerHTML = '<div class="alert alert-info">No showtimes available for this movie.</div>';
                    showtimeList.classList.remove('d-none');
                    return;
                }
                
                // Group showtimes by date
                const showtimesByDate = {};
                data.forEach(showtime => {
                    const date = new Date(showtime.showtime);
                    const dateStr = date.toLocaleDateString();
                    if (!showtimesByDate[dateStr]) {
                        showtimesByDate[dateStr] = [];
                    }
                    showtimesByDate[dateStr].push({
                        id: showtime.id,
                        hall_id: showtime.hall_id,
                        hall_name: showtime.hall ? showtime.hall.name : `Hall #${showtime.hall_id}`,
                        time: date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}),
                        price: showtime.ticket_price,
                        full_date: date
                    });
                });
                
                // Create date tabs
                let html = '<div class="showtime-dates mb-3"><ul class="nav nav-pills">';
                let first = true;
                Object.keys(showtimesByDate).forEach((date, index) => {
                    html += `<li class="nav-item">
                        <a class="nav-link ${first ? 'active' : ''}" 
                           href="#date-tab-${index}" 
                           data-bs-toggle="tab">${date}</a>
                    </li>`;
                    first = false;
                });
                html += '</ul></div>';
                
                // Create time slots for each date
                html += '<div class="tab-content">';
                first = true;
                Object.entries(showtimesByDate).forEach(([date, showtimes], index) => {
                    html += `<div class="tab-pane fade ${first ? 'show active' : ''}" id="date-tab-${index}">
                        <div class="row">`;
                    
                    showtimes.forEach(showtime => {
                        html += `<div class="col-md-3 mb-3">
                            <div class="card showtime-card" data-showtime-id="${showtime.id}" 
                                 data-hall-id="${showtime.hall_id}" data-price="${showtime.price}">
                                <div class="card-body">
                                    <h5 class="card-title">${showtime.time}</h5>
                                    <p class="card-text">${showtime.hall_name}</p>
                                </div>
                            </div>
                        </div>`;
                    });
                    
                    html += '</div></div>';
                    first = false;
                });
                html += '</div>';
                
                showtimeList.innerHTML = html;
                showtimeList.classList.remove('d-none');
                
                // Add event listeners to showtime cards
                document.querySelectorAll('.showtime-card').forEach(card => {
                    card.addEventListener('click', function() {
                        selectShowtime(
                            this.dataset.showtimeId,
                            this.dataset.hallId,
                            parseFloat(this.dataset.price)
                        );
                    });
                });
            })
            .catch(error => {
                loadingIndicator.classList.add('d-none');
                showtimeList.innerHTML = `<div class="alert alert-danger">Error fetching showtimes: ${error.message}</div>`;
                showtimeList.classList.remove('d-none');
                console.error('Error fetching showtimes:', error);
            });
    }

    // Select a showtime
    function selectShowtime(showtimeId, hallId, price) {
        // Highlight selected showtime
        document.querySelectorAll('.showtime-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const selectedCard = document.querySelector(`.showtime-card[data-showtime-id="${showtimeId}"]`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
            
            // Update state
            state.selectedShowtime = {
                id: showtimeId,
                hall_id: hallId,
                price: price
            };
            state.selectedSeats = [];
            
            // Update order summary
            updateOrderSummary();
            
            // Go to next step
            showStep(3);
        }
    }

    // Fetch seats for a hall
    function fetchSeats(hallId) {
        const isTestEnvironment = window.location.pathname.includes('pos-test.html');
        
        if (isTestEnvironment) {
            console.log('Using mock data for seats');
            const seatsContainer = document.getElementById('seats-container');
            const loadingIndicator = document.getElementById('seats-loading');
            const selectedSeatsContainer = document.getElementById('selected-seats');
            
            if (!seatsContainer || !loadingIndicator) return;
            
            // Show loading indicator
            seatsContainer.innerHTML = '';
            if (selectedSeatsContainer) selectedSeatsContainer.innerHTML = '';
            loadingIndicator.classList.remove('d-none');
            
            // Simulate network delay
            setTimeout(() => {
                loadingIndicator.classList.add('d-none');
                
                const data = MOCK_DATA.seats[hallId] || [];
                
                if (data.length === 0) {
                    seatsContainer.innerHTML = '<div class="alert alert-info">No seats available in this hall.</div>';
                    return;
                }
                
                // Group seats by row
                const seatsByRow = {};
                data.forEach(seat => {
                    if (!seatsByRow[seat.row]) {
                        seatsByRow[seat.row] = [];
                    }
                    seatsByRow[seat.row].push(seat);
                });
                
                // Create seat map
                let html = '<div class="seat-map">';
                html += '<div class="screen mb-4">SCREEN</div>';
                
                // Sort rows alphabetically
                const sortedRows = Object.keys(seatsByRow).sort();
                
                sortedRows.forEach(row => {
                    html += `<div class="seat-row">
                        <div class="row-label">${row}</div>
                        <div class="seats">`;
                    
                    // Sort seats by number
                    const sortedSeats = seatsByRow[row].sort((a, b) => a.number - b.number);
                    
                    sortedSeats.forEach(seat => {
                        const seatClass = seat.is_booked ? 'booked' : 
                                        (seat.type === 'premium' ? 'premium' : 
                                        (seat.type === 'vip' ? 'vip' : ''));
                        
                        html += `<div class="seat ${seatClass}" 
                                    data-seat-id="${seat.id}" 
                                    data-seat-row="${seat.row}" 
                                    data-seat-number="${seat.number}"
                                    data-seat-type="${seat.type}"
                                    data-seat-price="${calculateSeatPrice(seat.type)}"
                                    ${seat.is_booked ? 'disabled' : ''}>
                                    ${seat.number}
                                </div>`;
                    });
                    
                    html += '</div></div>';
                });
                
                html += '</div>';
                
                seatsContainer.innerHTML = html;
                
                // Add event listeners to seats
                document.querySelectorAll('.seat:not(.booked)').forEach(seatElem => {
                    seatElem.addEventListener('click', function() {
                        if (this.classList.contains('selected')) {
                            // Deselect seat
                            this.classList.remove('selected');
                            removeSeat(this.dataset.seatId);
                        } else {
                            // Select seat
                            this.classList.add('selected');
                            addSeat(
                                this.dataset.seatId,
                                this.dataset.seatRow,
                                this.dataset.seatNumber,
                                this.dataset.seatType,
                                parseFloat(this.dataset.seatPrice)
                            );
                        }
                    });
                });
            }, 1000);
            
            return;
        }
        
        // If not in test environment, use the original implementation
        const seatMap = document.getElementById('seat-map');
        const seatMapContainer = document.getElementById('seat-map-container');
        const seatSelectionContainer = document.getElementById('seat-selection-container');
        const loadingIndicator = seatSelectionContainer.querySelector('.loading-indicator');
        
        if (!seatMap || !seatMapContainer || !loadingIndicator) return;
        
        // Show loading indicator
        seatMapContainer.classList.add('d-none');
        loadingIndicator.classList.remove('d-none');
        
        // Fetch seats from server
        fetch(`/dashboard/pos/get-hall-seats/${hallId}?movie_id=${state.selectedMovie.id}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('d-none');
                
                if (data.length === 0) {
                    seatMap.innerHTML = '<div class="alert alert-info">No seats available in this hall.</div>';
                    seatMapContainer.classList.remove('d-none');
                    return;
                }
                
                // Group seats by row
                const seatsByRow = {};
                data.forEach(seat => {
                    if (!seatsByRow[seat.row]) {
                        seatsByRow[seat.row] = [];
                    }
                    seatsByRow[seat.row].push(seat);
                });
                
                // Create seat map
                let html = '';
                
                // Sort rows alphabetically
                const sortedRows = Object.keys(seatsByRow).sort();
                
                sortedRows.forEach(row => {
                    html += `<div class="seat-row">
                        <div class="row-label">${row}</div>
                        <div class="seats">`;
                    
                    // Sort seats by number
                    const sortedSeats = seatsByRow[row].sort((a, b) => a.number - b.number);
                    
                    sortedSeats.forEach(seat => {
                        const seatClass = seat.is_booked ? 'booked' : 
                                        (seat.type === 'premium' ? 'premium' : 
                                        (seat.type === 'vip' ? 'vip' : ''));
                        
                        const seatPrice = seat.calculated_price !== null ? 
                            seat.calculated_price : 
                            calculateSeatPrice(seat.type);
                        
                        html += `<div class="seat ${seatClass}" 
                                    data-seat-id="${seat.id}" 
                                    data-seat-row="${seat.row}" 
                                    data-seat-number="${seat.number}"
                                    data-seat-type="${seat.type}"
                                    data-seat-price="${seatPrice}"
                                    ${seat.is_booked ? 'disabled' : ''}>
                                    ${seat.number}
                                    ${seat.calculated_price > 0 ? `<div class="seat-price">$${parseFloat(seat.calculated_price).toFixed(2)}</div>` : ''}
                                </div>`;
                    });
                    
                    html += '</div></div>';
                });
                
                seatMap.innerHTML = html;
                seatMapContainer.classList.remove('d-none');
                
                // Add event listeners to seats
                document.querySelectorAll('.seat:not(.booked)').forEach(seatElem => {
                    seatElem.addEventListener('click', function() {
                        if (this.classList.contains('selected')) {
                            // Deselect seat
                            this.classList.remove('selected');
                            removeSeat(this.dataset.seatId);
                        } else {
                            // Select seat
                            this.classList.add('selected');
                            addSeat(
                                this.dataset.seatId,
                                this.dataset.seatRow,
                                this.dataset.seatNumber,
                                this.dataset.seatType,
                                parseFloat(this.dataset.seatPrice)
                            );
                        }
                    });
                });
            })
            .catch(error => {
                loadingIndicator.classList.add('d-none');
                seatMap.innerHTML = `<div class="alert alert-danger">Error fetching seats: ${error.message}</div>`;
                seatMapContainer.classList.remove('d-none');
                console.error('Error fetching seats:', error);
            });
    }

    // Calculate price based on seat type
    function calculateSeatPrice(seatType) {
        // Return 0 as we're now using the seat's additional_charge from the API
        return 0;
    }

    // Add a seat to selected seats
    function addSeat(id, row, number, type, price) {
        // Check if already selected
        if (state.selectedSeats.find(seat => seat.id === id)) {
            return;
        }
        
        // Add to state
        state.selectedSeats.push({
            id: id,
            row: row,
            number: number,
            type: type,
            price: price
        });
        
        // Update selected seats display
        updateSelectedSeats();
        
        // Update order summary
        updateOrderSummary();
    }

    // Remove a seat from selected seats
    function removeSeat(id) {
        state.selectedSeats = state.selectedSeats.filter(seat => seat.id !== id);
        
        // Update selected seats display
        updateSelectedSeats();
        
        // Update order summary
        updateOrderSummary();
    }

    // Update selected seats display
    function updateSelectedSeats() {
        const container = document.getElementById('selected-seats');
        if (!container) return;
        
        if (state.selectedSeats.length === 0) {
            container.innerHTML = '<p class="text-muted">No seats selected</p>';
            return;
        }
        
        // Group seats by type for better organization
        const seatsByType = {
            'regular': [],
            'premium': [],
            'vip': []
        };
        
        state.selectedSeats.forEach(seat => {
            if (!seatsByType[seat.type]) seatsByType[seat.type] = [];
            seatsByType[seat.type].push(seat);
        });
        
        let html = '<div class="mb-2">Selected Seats:</div>';
        
        // Process each seat type
        Object.keys(seatsByType).forEach(type => {
            if (seatsByType[type].length === 0) return;
            
            // Capitalize first letter of type
            const typeLabel = type.charAt(0).toUpperCase() + type.slice(1);
            
            // Sort seats by row and number
            const sortedSeats = seatsByType[type].sort((a, b) => {
                if (a.row === b.row) return a.number - b.number;
                return a.row.localeCompare(b.row);
            });
            
            sortedSeats.forEach(seat => {
                let typeClass = '';
                if (seat.type === 'premium') typeClass = 'bg-info text-dark';
                if (seat.type === 'vip') typeClass = 'bg-danger text-white';
                
                html += `<span class="selected-seat-tag ${typeClass}">
                    ${seat.row}${seat.number} (${typeLabel})
                    <button type="button" class="btn-close btn-sm ms-1" 
                        data-seat-id="${seat.id}"></button>
                </span>`;
            });
        });
        
        container.innerHTML = html;
        
        // Add event listeners to remove buttons
        document.querySelectorAll('.selected-seat-tag .btn-close').forEach(btn => {
            btn.addEventListener('click', function() {
                const seatId = this.dataset.seatId;
                
                // Deselect the seat in the map
                const seatElement = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
                if (seatElement) {
                    seatElement.classList.remove('selected');
                }
                
                // Remove from state
                removeSeat(seatId);
            });
        });
    }

    // Add food or drink item to order
    function addItem(type, id, name, price) {
        const collection = type === 'food' ? state.selectedFoodItems : state.selectedDrinks;
        const existingItem = collection.find(item => item.id === id);
        
        if (existingItem) {
            existingItem.quantity += 1;
            existingItem.subtotal = existingItem.quantity * price;
        } else {
            collection.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
                subtotal: price
            });
        }
        
        // Update quantity display
        const qtyElement = document.querySelector(`#${type}-qty-${id}`);
        if (qtyElement) {
            const item = collection.find(item => item.id === id);
            qtyElement.textContent = item ? item.quantity : 0;
        }
        
        // Update order summary
        updateOrderSummary();
    }

    // Remove food or drink item from order
    function removeItem(type, id) {
        const collection = type === 'food' ? state.selectedFoodItems : state.selectedDrinks;
        const existingItemIndex = collection.findIndex(item => item.id === id);
        
        if (existingItemIndex >= 0) {
            const item = collection[existingItemIndex];
            
            if (item.quantity > 1) {
                item.quantity -= 1;
                item.subtotal = item.quantity * item.price;
            } else {
                collection.splice(existingItemIndex, 1);
            }
            
            // Update quantity display
            const qtyElement = document.querySelector(`#${type}-qty-${id}`);
            if (qtyElement) {
                const updatedItem = collection.find(item => item.id === id);
                qtyElement.textContent = updatedItem ? updatedItem.quantity : 0;
            }
            
            // Update order summary
            updateOrderSummary();
        }
    }

    // Reset the order summary
    function resetOrderSummary() {
        state.selectedMovie = null;
        state.selectedShowtime = null;
        state.selectedSeats = [];
        state.selectedFoodItems = [];
        state.selectedDrinks = [];
        
        // Reset UI
        document.querySelectorAll('.movie-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        document.querySelectorAll('.qty-input').forEach(input => {
            input.value = 0;
        });
        
        updateOrderSummary();
    }

    // Update the order summary
    function updateOrderSummary() {
        // Calculate subtotals
        const ticketsTotal = state.selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
        const foodTotal = state.selectedFoodItems.reduce((sum, item) => sum + item.subtotal, 0);
        const drinksTotal = state.selectedDrinks.reduce((sum, item) => sum + item.subtotal, 0);
        const total = ticketsTotal + foodTotal + drinksTotal;
        
        // Update state
        state.orderSummary = {
            tickets: ticketsTotal,
            foodItems: foodTotal,
            drinks: drinksTotal,
            total: total
        };
        
        // Update HTML
        const subtotalAmount = document.getElementById('subtotal-amount');
        const totalAmount = document.getElementById('total-amount');
        
        if (subtotalAmount) subtotalAmount.textContent = '$' + total.toFixed(2);
        if (totalAmount) totalAmount.textContent = '$' + total.toFixed(2);
        
        // Update order items display
        const orderSummary = document.getElementById('order-summary');
        const orderItems = document.getElementById('order-items');
        
        if (orderSummary && orderItems) {
            if (!state.selectedMovie || !state.selectedShowtime || state.selectedSeats.length === 0) {
                // Show empty state
                orderSummary.classList.remove('d-none');
                orderItems.classList.add('d-none');
            } else {
                // Show order items
                orderSummary.classList.add('d-none');
                orderItems.classList.remove('d-none');
                
                // Update movie details
                const movieTitle = document.querySelector('.selected-movie-title');
                const movieDetails = document.querySelector('.selected-movie-details');
                const showtime = document.querySelector('.selected-showtime');
                const hall = document.querySelector('.selected-hall');
                
                if (movieTitle) movieTitle.textContent = state.selectedMovie.title;
                if (movieDetails) movieDetails.textContent = `${state.selectedMovie.duration} min | ${state.selectedMovie.genre}`;
                
                // Format date and time
                if (showtime && state.selectedShowtime) {
                    const showtimeCard = document.querySelector(`.showtime-card[data-showtime-id="${state.selectedShowtime.id}"]`);
                    if (showtimeCard) {
                        const time = showtimeCard.querySelector('.card-title').textContent;
                        const hallName = showtimeCard.querySelector('.card-text').textContent;
                        
                        showtime.textContent = time;
                        if (hall) hall.textContent = hallName;
                    }
                }
                
                // Update selected seats
                const selectedSeatsList = document.getElementById('selected-seats-list');
                if (selectedSeatsList) {
                    selectedSeatsList.innerHTML = '';
                    
                    if (state.selectedSeats.length === 0) {
                        selectedSeatsList.innerHTML = '<p class="text-muted">No seats selected</p>';
                    } else {
                        state.selectedSeats.forEach(seat => {
                            const seatTag = document.createElement('div');
                            seatTag.className = 'selected-seat-tag';
                            seatTag.textContent = `${seat.row}${seat.number}`;
                            selectedSeatsList.appendChild(seatTag);
                        });
                    }
                }
                
                // Update food and drinks
                const concessionsList = document.getElementById('selected-concessions-list');
                if (concessionsList) {
                    concessionsList.innerHTML = '';
                    
                    if (state.selectedFoodItems.length === 0 && state.selectedDrinks.length === 0) {
                        concessionsList.innerHTML = '<li class="list-group-item text-muted">No items selected</li>';
                    } else {
                        // Add food items
                        state.selectedFoodItems.forEach(item => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                            listItem.innerHTML = `
                                <div>
                                    <span class="fw-bold">${item.name}</span>
                                    <span class="text-muted">× ${item.quantity}</span>
                                </div>
                                <span>$${item.subtotal.toFixed(2)}</span>
                            `;
                            concessionsList.appendChild(listItem);
                        });
                        
                        // Add drinks
                        state.selectedDrinks.forEach(item => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                            listItem.innerHTML = `
                                <div>
                                    <span class="fw-bold">${item.name}</span>
                                    <span class="text-muted">× ${item.quantity}</span>
                                </div>
                                <span>$${item.subtotal.toFixed(2)}</span>
                            `;
                            concessionsList.appendChild(listItem);
                        });
                    }
                }
            }
        }
    }

    // Finalize booking
    function finalizeBooking() {
        // Validate customer form
        const customerName = document.getElementById('customer-name');
        const customerEmail = document.getElementById('customer-email');
        const customerPhone = document.getElementById('customer-phone');
        
        if (!customerName || !customerEmail || !customerPhone) return;
        
        // Simple validation
        if (!customerName.value.trim()) {
            alert('Please enter customer name.');
            customerName.focus();
            return;
        }
        
        if (!customerEmail.value.trim()) {
            alert('Please enter customer email.');
            customerEmail.focus();
            return;
        }
        
        if (!customerPhone.value.trim()) {
            alert('Please enter customer phone number.');
            customerPhone.focus();
            return;
        }
        
        // Save customer info to state
        state.customerInfo = {
            name: customerName.value.trim(),
            email: customerEmail.value.trim(),
            phone: customerPhone.value.trim(),
            notes: document.getElementById('notes') ? document.getElementById('notes').value.trim() : ''
        };
        
        // Move to payment step
        showStep(6);
        
        // Generate QR code
        generatePaymentQR();
    }
    
    // Generate payment QR code
    function generatePaymentQR() {
        const paymentAmount = document.getElementById('payment-amount');
        
        if (!paymentAmount) return;
        
        // Display payment amount
        paymentAmount.textContent = '$' + state.orderSummary.total.toFixed(2);
        
        // Generate a random booking ID
        const bookingId = 'BK-' + Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
        state.bookingId = bookingId;
        
        // Set up simulate payment button
        const simulatePaymentBtn = document.getElementById('simulate-payment-btn');
        if (simulatePaymentBtn) {
            simulatePaymentBtn.addEventListener('click', processPayment);
        }
    }
    
    // Process payment (simulation)
    function processPayment() {
        // Show loading state
        const simulatePaymentBtn = document.getElementById('simulate-payment-btn');
        if (simulatePaymentBtn) {
            simulatePaymentBtn.disabled = true;
            simulatePaymentBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        }

        // Prepare booking data
        const bookingData = {
            showtime_id: state.selectedShowtime.id,
            seats: state.selectedSeats.map(seat => ({
                seat_id: seat.id,
                price: seat.price
            })),
            food_items: state.selectedFoodItems.map(item => ({
                item_id: item.id,
                quantity: item.quantity,
                price: item.price
            })),
            drinks: state.selectedDrinks.map(item => ({
                item_id: item.id,
                quantity: item.quantity,
                price: item.price
            })),
            customer: state.customerInfo,
            total_amount: state.orderSummary.total,
            booking_id: state.bookingId
        };

        // Send booking data to server
        fetch('/dashboard/pos/save-booking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Store booking data for receipt
                state.bookingData = data.booking;
                
                // Move to receipt step
                showStep(7);
                
                // Populate receipt
                populateReceipt();
            } else {
                throw new Error(data.message || 'Failed to save booking');
            }
        })
        .catch(error => {
            alert('Error saving booking: ' + error.message);
            console.error('Booking error:', error);
        })
        .finally(() => {
            // Reset button state
            if (simulatePaymentBtn) {
                simulatePaymentBtn.disabled = false;
                simulatePaymentBtn.innerHTML = '<i class="fas fa-money-bill-wave me-1"></i> Process Payment';
            }
        });
    }
    
    // Populate receipt with booking details
    function populateReceipt() {
        // Set receipt details
        document.getElementById('receipt-booking-id').textContent = state.bookingId;
        document.getElementById('receipt-movie-title').textContent = state.selectedMovie.title;
        
        const showtimeEl = document.querySelector('.selected-showtime');
        const hallEl = document.querySelector('.selected-hall');
        document.getElementById('receipt-showtime').textContent = showtimeEl ? showtimeEl.textContent : '';
        document.getElementById('receipt-hall').textContent = hallEl ? hallEl.textContent : '';
        
        // Format seats (e.g., "A1, A2, B3")
        const seatsFormatted = state.selectedSeats
            .sort((a, b) => a.row === b.row ? a.number - b.number : a.row.localeCompare(b.row))
            .map(seat => `${seat.row}${seat.number}`)
            .join(', ');
        document.getElementById('receipt-seats').textContent = seatsFormatted;
        
        // Customer info
        document.getElementById('receipt-customer-name').textContent = state.customerInfo.name;
        document.getElementById('receipt-customer-email').textContent = state.customerInfo.email;
        document.getElementById('receipt-customer-phone').textContent = state.customerInfo.phone;
        
        // Add items to receipt
        const receiptItems = document.getElementById('receipt-items');
        receiptItems.innerHTML = '';
        
        // Add tickets
        state.selectedSeats.forEach(seat => {
            const seatType = seat.type.charAt(0).toUpperCase() + seat.type.slice(1);
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>Ticket (${seatType} - ${seat.row}${seat.number})</td>
                <td class="text-end">1</td>
                <td class="text-end">$${seat.price.toFixed(2)}</td>
                <td class="text-end">$${seat.price.toFixed(2)}</td>
            `;
            receiptItems.appendChild(row);
        });
        
        // Add food items
        state.selectedFoodItems.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td class="text-end">${item.quantity}</td>
                <td class="text-end">$${item.price.toFixed(2)}</td>
                <td class="text-end">$${item.subtotal.toFixed(2)}</td>
            `;
            receiptItems.appendChild(row);
        });
        
        // Add drinks
        state.selectedDrinks.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td class="text-end">${item.quantity}</td>
                <td class="text-end">$${item.price.toFixed(2)}</td>
                <td class="text-end">$${item.subtotal.toFixed(2)}</td>
            `;
            receiptItems.appendChild(row);
        });
        
        // Set totals
        document.getElementById('receipt-subtotal').textContent = '$' + state.orderSummary.total.toFixed(2);
        document.getElementById('receipt-total').textContent = '$' + state.orderSummary.total.toFixed(2);
        
        // Set up print button
        const printReceiptBtn = document.getElementById('print-receipt-btn');
        if (printReceiptBtn) {
            printReceiptBtn.addEventListener('click', () => {
                window.print();
            });
        }
        
        // Set up new booking button
        const newBookingBtn = document.getElementById('new-booking-from-receipt-btn');
        if (newBookingBtn) {
            newBookingBtn.addEventListener('click', () => {
                resetOrderSummary();
                showStep(1);
            });
        }
    }

    // Initialize the page when DOM is loaded
    initializePage();
}); 